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
	
	/**
	 * 检测是否手机
	 */
	public static function _isMobile($pattern = '/iPhone|Android/i')
	{
		return isset($_SERVER['HTTP_USER_AGENT']) && preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
	}
}
