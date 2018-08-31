<?php
namespace Astro;

class Core
{
	public static $_instance;
	
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
	 * User Agent - 检测是否手机
	 */
	public static function _isMobile($pattern = '/iPhone|Android/i', $ua = null)
	{
		$ua = $ua ? : (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');
		return preg_match($pattern, $ua);
	}
	
	/**
	 * User Agent - 特定APP检测
	 */
	public static function _userAgent($ua = null)
	{
		$ua = $ua ? : $_SERVER['HTTP_USER_AGENT'];
		$device = '未知APP';
		$client = null;
		$version = null;
		if (preg_match("/AliApp\((TB|AP)\/([0-9\.]+)\)/i", $ua, $matches)) {
			$client = $matches[1];
			$version = $matches[2];
			
		} elseif (preg_match("/(MicroMessenger|QQ)/i", $ua, $matches)) {
			$client = $matches[1];
			$device = $matches[1];
		}
		return [$client, $device, $version];
	}
	
	/**
	 * 自定义初始化
	 */
	public function _init()
	{
		//
	}
	
	public function _set($arg = [])
	{
		
		foreach ($arg as $key => $value) {
			$this->$key = $value;
		}
	}
	
	/**
	 * 获取数组键值
	 */
	public static function _var($arr = [], $key = 0, $default = null)
	{
		if ($arr && isset($arr[$key]) && $value = $arr[$key]) {
			return $value;
		}
		return $default;
	}
}
