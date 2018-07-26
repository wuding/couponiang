<?php
namespace Astro;

class Php
{
	public static $inst;
	public static $templet;
	public $instance;
	public $template;
	public $routeCollector = null;
	public $dispatcher = null;
	public $controller = null;
	public $config = [
		'route' => [],
	];
	
	public function __construct($config = null)
	{
		if ($config) {
			if (is_string($config)) {
				$this->getConfig($config);
			} elseif (is_array($config)) {
				$this->config = $config;
			} else {
				print_r([$config, __METHOD__, __LINE__, __FILE__]);
			}
		}
		$this->httpMethod = $_SERVER['REQUEST_METHOD'];
		$uri = $_SERVER['REQUEST_URI'];

		// Strip query string (?foo=bar) and decode URI
		if (false !== $pos = strpos($uri, '?')) {
			$uri = substr($uri, 0, $pos);
		}
		$this->uri = $uri = rawurldecode($uri);
		
		# print_r([__METHOD__, __LINE__, __FILE__]);
	}
	
	public static function getInst($config = null)
	{
		if (null != self::$inst) {
			return self::$inst;
		}
		return self::$inst = new Php($config);
	}
	
	public function getInstance($config = null)
	{
		$inst =& self::$inst;
		if (null != $inst) {
			return $inst;
		}
		return $this->instance = $inst = new Php($config);
	}
	
	################ 配置 ################
	public function getConfig($file = null)
	{
		if (!$file) {
			$file = $this->configFile;
		}
		$this->configFile = $file;
		return $this->config = include_once $file;
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
			['GET', '/search', '_module/_controller']
		];
		
		$routeRules = $this->config['route'];
		if (is_array($routeRules)) {
			foreach ($routeRules as $rule) {
				$r->addRoute($rule[0], $rule[1], $rule[2]);
			}
		}
	}
	
	/************** 调度器 ***************/
	public function dispatcher($routeInfo = [], $requestInfo = [], $controllerVars = [])
	{
		if (null !== $this->dispatcher) {
			return $this->dispatcher;
		}
		return $this->dispatcher = new Dispatcher($routeInfo, $requestInfo, $controllerVars);
	}
	
	/************** 控制器 ***************/
	public function controller($routeInfo = [], $requestInfo = [], $controllerVars = [], $last = true)
	{
		$routeInfo = $routeInfo ? : $this->routeInfo;
		$requestInfo = $requestInfo ? : ['method' => $this->httpMethod, 'uri' => $this->uri];
		
		if ($last && null !== $this->controller) {
			return $this->controller;
		}
		$dispatcher = $this->dispatcher();
		$uniqueId = $dispatcher->init($routeInfo, $requestInfo, $controllerVars);
		return $this->controller = $dispatcher->getController();
	}
	
	/************** 内置函数 ***************/
	public static function getArrayVar($arr = [], $key = 0, $default = null)
	{
		if ($arr && isset($arr[$key]) && $value = $arr[$key]) {
			return $value;
		}
		return $default;
	}
	
	public static function getUniqueId($routeInfo = [], $requestInfo = [], $controllerVars = [])
	{
		$req = md5(json_encode($requestInfo));
		$var = md5(json_encode($controllerVars));
		return $unique = Php::getArrayVar($routeInfo, 0, '') . '_' . Php::getArrayVar($routeInfo, 1, '') . '_' . $req . '_' . $var;
	}
	
	/************** 模板引擎 *************/
	public function template()
	{
		# $tpl =& $this->template;
		$tpl =& self::$templet;
		if (null != $tpl) {
			return $tpl;
		}
		return $this->template = $tpl = new \League\Plates\Engine(APP_PATH . '/template');
	}
	
	public function __destruct()
	{
		$routeInfo = $this->routeInfo();
		$controller = $this->controller();
		if (!$controller->exec && 2 == $controller->destruct) {
			$controller->_destruct(null, null, 0);
		}
		# 
		# $this->dispatcher()->getController([1, 'test/a/url', []])->_destruct();
		# print_r([$routeInfo, $controller, __METHOD__, __LINE__, __FILE__]);
		# $GLOBALS['PHP'] = $this;
	}
}
