<?php
namespace Astro;

class Php
{
	public function __construct()
	{
		print_r([__METHOD__, __LINE__, __FILE__]);
	}
	
	public function __destruct()
	{
		print_r([__METHOD__, __LINE__, __FILE__]);
	}
}
