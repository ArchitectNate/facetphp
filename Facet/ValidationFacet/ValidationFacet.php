<?php

namespace FacetPHP\Facet;

use FacetPHP\Core\Facet;
use FacetPHP\Core\FacetInterface;

class ValidationFacet extends Facet implements FacetInterface
{
	
	public function _initFacet()
	{
		echo "TEST";
	}
	
	public function validateEmail($email)
	{
	
	}
	
	public function validatePassword($password, $securityLevel)
	{
	
	}
	
	public function validatePhone($phone)
	{
	
	}
}