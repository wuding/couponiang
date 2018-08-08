<?php
namespace app\module\_module\controller;

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
		return [__METHOD__, __LINE__, __FILE__];
	}
	
	/* HTTP方法动作 */
	public function _get__action()
	{
		return [__METHOD__, __LINE__, __FILE__];
	}
	
	/* HTTP映射动作 */
	public function _get_()
	{
		# print_r([__METHOD__, __LINE__, __FILE__], true)
		
		$destruct = 2;
		$routeInfo = [1, 'test/a/url', []];
		$requestInfo = ['method' => 'get', 'uri' => '/test/a/url'];
		$options = [
			'return' => 1, 
			'exit' => 'text',
			'vars' => [
				'destruct' => $destruct
			]
		];
		
		$var = [];
		$var = $this->_forward($routeInfo, $requestInfo, $options, $destruct);# exit;
		
		# $this->exit = \Astro\func('some');
		return [$var, __METHOD__, __LINE__, __FILE__];
	}
	
	/**/
	public function search()
	{
		print_r([__METHOD__, __LINE__, __FILE__]);exit;
	}
	
}
