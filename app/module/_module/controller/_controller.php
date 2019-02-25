<?php
namespace app\module\_module\controller;

use app\model\SearchTerms;
use OpenSearch\Description;
use OpenSearch\Suggestions;

class _Controller extends \Astro\Controller
{
	
	# public $exit = 'so'; //执行后退出
	public $destruct = 2;# 
	
	public function __construct($action = '', $method = '', $vars = [])
	{
		# $vars['custom'] = 'value';
		parent::__construct($action, $method, $vars);		
	}
	
	/* 缺省动作 */
	public function _action()
	{
		return [__METHOD__, __LINE__, __FILE__];
	}
	
	/* HTTP方法动作 */
	public function _get__action()
	{
		global $PHP;
		if ('/play' == $PHP->requestUri) {
			$url = 'http://cpn.red' . $_SERVER['REQUEST_URI'];
			header('Location: ' . $url);exit;
		}
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


	public function suggestions()
	{
		$this->disableView = 'echo';
    	$this->mobileSuffix = '';
    	$this->responseHeaders = ['Content-Type: application/json'];

    	# $config = include APP_PATH . '/config/opensearch.php';
    	# print_r(get_defined_vars());exit;

		/* 定义 */
		$query = trim($this->_get('q'));

		$Terms = new SearchTerms;
		$arr = $Terms->view($query);
		$suggestions = new Suggestions($arr, $query); #, Description::template($search_url)
		$suggestions->configFile(APP_PATH . '/config/opensearch.php');
		return $json = $suggestions->json(); # 
		print_r($suggestions);
		print_r([$arr, $json, __METHOD__, __LINE__, __FILE__]);exit;
	}
}
