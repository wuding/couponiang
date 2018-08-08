<?php
namespace app\module\item\controller;

class _Controller extends \Astro\Controller
{
	
	# public $exit = 'so'; //执行后退出
	public $destruct = 2;# 
	
	public function __construct($action = '', $method = '', $vars = [])
	{
		# $vars['custom'] = 'value';
		parent::__construct($action, $method, $vars);
		# print_r([__METHOD__, __LINE__, __FILE__]); 
	}
	
	/* 缺省动作 */
	public function _action()
	{
		print_r([__METHOD__, __LINE__, __FILE__]); # 
	}
	
	/* HTTP方法动作 */
	public function _get__action()
	{
		print_r([__METHOD__, __LINE__, __FILE__]); # 
	}
	
	/* HTTP映射动作 */
	public function _get_()
	{
		print_r([__METHOD__, __LINE__, __FILE__]); # 
	}
	
	public function index()
	{
		print_r([__METHOD__, __LINE__, __FILE__]); # 
	}
}
