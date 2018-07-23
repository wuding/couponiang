<?php
namespace Astro;

class Php
{
	public $routeCollector = null;
	public $dispatcher = null;
	public $controller = null;
	
	public function __construct()
	{
		$this->httpMethod = $_SERVER['REQUEST_METHOD'];
		$uri = $_SERVER['REQUEST_URI'];

		// Strip query string (?foo=bar) and decode URI
		if (false !== $pos = strpos($uri, '?')) {
			$uri = substr($uri, 0, $pos);
		}
		$this->uri = $uri = rawurldecode($uri);
		
		# print_r([__METHOD__, __LINE__, __FILE__]);
	}
	
	/*************** 路由 ***************/
	public function routeInfo()
	{
		$GLOBALS['PHP'] = $this;
		# print_r([get_class_methods($PHP), __METHOD__, __LINE__, __FILE__]);
		# $router = $this->router();
		return $this->routeInfo = $this->router()->dispatch($this->httpMethod, strtolower($this->uri));
	}
	
	public function router()
	{
		return $this->router = \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) {
			$GLOBALS['PHP']->routeRule($r);
			# print_r([get_class_methods($PHP), $PHP, __METHOD__, __LINE__, __FILE__]);
		});
	}
	
	public function routeRule($r = null)
	{
		if ($r) {
			if (null == $this->routeCollector) {
				$this->routeCollector = $r;
			}
		} else {
			$r = $this->routeCollector;
		}
		
		$routeRules = [
			[['GET', 'POST', 'PUT'], '/[index]', '/index/index'],
			['GET', '/search', '/search']
		];
		foreach ($routeRules as $rule) {
			$r->addRoute($rule[0], $rule[1], $rule[2]);
		}
	}
	
	/************** 调度器 ***************/
	public function dispatcher()
	{
		if (null != $this->dispatcher) {
			return $this->dispatcher;
		}
		return $this->dispatcher = new Dispatcher($this->routeInfo, ['method' => $this->httpMethod, 'uri' => $this->uri]);
	}
	
	/************** 控制器 ***************/
	public function controller()
	{
		if (null != $this->controller) {
			return $this->controller;
		}
		return $this->controller = $this->dispatcher()->getController();
	}
	
	/************** 内置函数 ***************/
	public static function getArrayVar($arr = [], $key = 0, $default = null)
	{
		if ($arr && isset($arr[$key]) && $value = $arr[$key]) {
			return $value;
		}
		return $default;
	}
	
	public function __destruct()
	{
		$routeInfo = $this->routeInfo();
		$this->controller()->_destruct();
		# print_r([$routeInfo, __METHOD__, __LINE__, __FILE__]);
		
	}
}
