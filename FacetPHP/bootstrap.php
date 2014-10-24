<?php
// Path relative to the webroot
define('FACETPHP_INSTALL', $_SERVER['DOCUMENT_ROOT'] . '/facet_fw/facetphp');
define('FACETPHP_CORE', FACETPHP_INSTALL . '/FacetPHP/Core');
define('FACETPHP_BIT', FACETPHP_INSTALL . '/FacetPHP/Bit');
define('FACETPHP_FACET', FACETPHP_INSTALL . '/FacetPHP/Facet');


/**
 * Facet Core specific files
 */
spl_autoload_register(function ($coreClass)
{
	$fullClassPath = FACETPHP_INSTALL . "/" . str_replace("\\", "/", $coreClass);	

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
	foreach (glob(FACETPHP_CORE . "/third-party/*/" . $thirdPartyClass . ".php") as $filename) {
		require_once($filename);
	}
});

\FacetPHP\Core\Facet::_init();
?>
