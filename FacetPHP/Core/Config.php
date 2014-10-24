<?php
namespace FacetPHP\Core;

class Config
{
	private $_config = array();
	private $_configPath = null;
	
	public function __construct($configPath)
	{
		$this->_configPath = $configPath;
		$this->_parseConfig();
	}
	
	public function get($name)
	{
		try {
			if (!is_string($name)) {
				throw new \Exception('Config attribute names can only be strings');
			}
			
			if (!isset($this->_config[$name])) {
				throw new \Exception('[' . $name . '] is not a config attribute.');
			}
			
			return $this->_config[$name];
			
		} catch(\Exception $e) {
			Facet::_report($e);
		}
	}
	
	private function _parseConfig()
	{
		try {
			if (!file_exists($this->_configPath)) {
				throw new \Exception("Config file cannot be found, expecting {$this->_configPath}");
			}

			$configData = \Spyc::YAMLLoad($this->_configPath);
			$this->_recursiveParse($configData);
			
		} catch(\Exception $e) {
			Facet::_report($e);
		}
	}
	
	private function _recursiveParse($config, $parentName = '')
	{
		foreach ($config as $attr => $val) {
			$configAttributePath = (empty($parentName) ? $attr : $parentName . '.' . $attr);
			
			if (is_array($val)) {
				$this->_recursiveParse($val, $configAttributePath);
			} else {
				$this->_config[$configAttributePath] = $val;
			}
		}
	}
}
?>