<?php
namespace Astro;

class Core
{
	/**
	 * 获取查询
	 *
	 */
	public function _get($name = null, $value = null, $null = null)
	{
		if (isset($_GET[$name])) {			 
			$val = $_GET[$name];
			if ($val) {
				$value = $val;
			}
		}
		return $value;
	}
}
