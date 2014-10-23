<?php
// Absolute webroot path
define('FACETPHP_WEBROOT', 'C:/xampp/htdocs/');

// Paths relative to webroot
define('FACETPHP_INSTALL', 'facet_fw/');
define('FACETPHP_CORE', FACETPHP_INSTALL . 'FacetPHP/Core/');
define('FACETPHP_BIT', FACETPHP_INSTALL . 'FacetPHP/Bit/');
define('FACETPHP_FACET', FACETPHP_INSTALL . 'FacetPHP/Facet/');


/**
 * Facet Core specific files
 */
spl_autoload_register(function ($coreClass)
{
	$fullClassPath = FACETPHP_WEBROOT . FACETPHP_INSTALL . str_replace("\\", "/", $coreClass);	
	
	if(file_exists($fullClassPath . '.php'))
	{
		require_once($fullClassPath . '.php');
		return;
	}
});

/**
 * Third party classes
 */
spl_autoload_register(function ($thirdPartyClass)
{
	foreach (glob(FACETPHP_WEBROOT .  FACETPHP_CORE . "third-party/*/" . $thirdPartyClass . ".php") as $filename) {
		require_once($filename);
	}
});

/**
 * General Facets
 */
spl_autoload_register(function ($facetClass) 
{
	$separatedClassPath = explode("\\", $facetClass);
	$separatedClassPath[] = end($separatedClassPath);
	$fullClassPath = implode("/", $separatedClassPath);
		
	if(file_exists($fullClassPath . '.php'))
	{
		require_once($fullClassPath . '.php');
		return;
	}
});

\FacetPHP\Core\Facet::_init();
?>