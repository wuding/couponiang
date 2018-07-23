<?php
namespace Astro;

class Php
{
	public $routeCollector = null;
	
	public function __construct()
	{
		global $PHP;
		$PHP = $this;
		
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
		
		# print_r([get_class_methods($PHP), __METHOD__, __LINE__, __FILE__]);
		# $router = $this->router();
		return $this->routeInfo = $this->router()->dispatch($this->httpMethod, $this->uri);
	}
	
	public function router()
	{
		return $this->router = \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) {
			global $PHP;
			$PHP->routeRule($r);
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
		$r->addRoute(['GET', 'POST', 'PUT'], '/[index]', '/index/index');
	}
	
	public function __destruct()
	{
		$routeInfo = $this->routeInfo();
		print_r([$routeInfo, __METHOD__, __LINE__, __FILE__]);
		
	}
}
