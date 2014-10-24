<?php
namespace FacetPHP\Core;

use FacetPHP\Core\Config as Config;

abstract class Facet {

	protected static $_entities = array();
	protected $_config; // For individual facet's config
	
	/**
	 * Run in bootstrap
	 */
	final public static function _init()
	{
		try {
			self::_fwOnly('_init');
			self::_registerEntity('config', self::_loadCoreConfig());
			self::_registerEntity('facetlist', self::_generateFacetList());
		} catch (\Exception $e) {
			self::_report($e);
		}
	}
	
	/**
	 * This is for child facets
	 */
	public function __construct()
	{
		try {
			$this->_loadLocalConfig();
			$this->_registerMethods();
			if (method_exists($this, "_initFacet")) { // Redundancy in case the interface isn't implemented in the child facet
				$this->_initFacet();
			} else {
				throw new \Exception("Every facet must have an initFacet() method.");
			}
		} catch (\Exception $e) {
			self::_report($e);
		}
	}
	
	/**
	 * Retrieves a particular entity from the internal array
	 */
	protected function _entity($entity)
	{
		if (isset(self::$_entities[$entity])) {
			return self::$_entities[$entity];
		}
	}
	
	/**
	 * Returns the config for the local child facet
	 */
	protected function _getConfig()
	{
		return $this->_config;
	}
	
	/**
	 * Gets the list of publicly available methods
	 */
	protected function _registerMethods()
	{
		$methodList = array();
		$methods = get_class_methods($this);
		foreach($methods as $method)
		{
			if(substr($method, 0, 1) != '_')
			{
				$methodList[] = $method;
			}
		}
		
		$this->_registerEntity("methodlist", $methodList);
	}
	
	/**
	 * Load individual facet config
	 */
	private function _loadLocalConfig()
	{
		$this->_config = new Config($this->_getFacetPath() . "\config.yml");
	}
	
	/**
	 * Retrieves child class path
	 */
	private function _getFacetPath()
	{
		$reflector = new \ReflectionClass(get_class($this));
         return dirname($reflector->getFileName());
	}
	/**
	 * Loads the core config
	 */
	private static function _loadCoreConfig()
	{
		return new Config(FACETPHP_CORE . '/config.yml');
	}
	
	/**
	 * Analyses and collects the different facets available
	 */
	 private static function _generateFacetList()
	 {
		$facetList = array();
		foreach (glob(FACETPHP_FACET . "/*Facet", GLOB_ONLYDIR) as $filename) {
			$facetList[] = basename($filename);
		}
		return $facetList;
	 }
	
	/**
	 * Sets a particular entities value
	 */
	private static function _registerEntity($entityName, $entityValue)
	{
		self::$_entities[$entityName] = $entityValue;
	}
	
	/**
	 * Use to ensure extended classes can't run a specific method that needs to be public (like init())
	 */
	private static function _fwOnly($functionName)
	{
		if (get_called_class() != 'FacetPHP\Core\Facet')
		{
			throw new \Exception("The {$functionName}() function can only be called by the core facet class.");
		}
	}
	
	/**
	 * Basic error reporter
	 */
	public static function _report(\Exception $e) {
		print('Exception Caught: ' . $e->getMessage() . '<br /><br />[File] ' . $e->getFile() . '<br />[Line] ' . $e->getLine() . ' <br /><br />[Trace]<br />' . str_replace("\n", "<br />", $e->getTraceAsString()));
	}
}
?>