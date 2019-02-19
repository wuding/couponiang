<?php
namespace app\module\_module\controller;

class _Abstract extends \Astro\Controller
{
	public function _action()
	{
		print_r([__METHOD__, __LINE__, __FILE__]);
	}
}
