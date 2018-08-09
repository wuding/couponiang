<?php
namespace app\controller;

class _Abstract extends \Astro\Controller
{
	public $stat = null;
	
	/**
	 * 初始化
	 *
	 */
	public function _init()
	{
		// 网站统计
		$stat = 0;
		if (isset($_GET['stat'])) {
			$stat = $_GET['stat'];
			setcookie('stat', $stat, time()+60*60*24*30, '/');
		} elseif (isset($_COOKIE['stat'])) {
			$stat = $_COOKIE['stat'];
		}
		$this->stat = $stat;
	}
	
	/**
	 * 缺省动作
	 *
	 */
	public function _action()
	{
		print_r([__METHOD__, __LINE__, __FILE__]);
	}
}
