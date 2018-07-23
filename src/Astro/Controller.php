<?php
namespace Astro;

class Controller
{
	public function __construct()
	{
		# print_r([__METHOD__, __LINE__, __FILE__]);
	}
	
	public function _notfound()
	{
		print_r([__METHOD__, __LINE__, __FILE__]);
	}
	
	public function _destruct()
	{
		print_r([__METHOD__, __LINE__, __FILE__]);
	}
	
	public function __destruct()
	{
		if ($GLOBALS['_CONTROLLER']['destruct']) {
			$this->_destruct();
		}
	}
}
