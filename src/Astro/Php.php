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
	public $routeInfo = [0];
	
	/**
	 * 构造函数
	 *
	 * 导入配置和请求
	 */
	public function __construct($config = null)
	{
		// 配置
		if ($config) {
			if (is_string($config)) {
				$this->getConfig($config);
			} elseif (is_array($config)) {
				$this->config = $config + $this->config;
			} else {
				print_r([$config, __METHOD__, __LINE__, __FILE__]);
			}
		}
		
		// 请求
		$this->requestMethod = $_SERVER['REQUEST_METHOD'];
		$requestPath = $_SERVER['REQUEST_URI'];
		if (false !== $pos = strpos($requestPath, '?')) {
			$requestPath = substr($requestPath, 0, $pos);
		}
		$php_self = $_SERVER['PHP_SELF'];
		$path_self = dirname($php_self);
		$pattern = addcslashes($path_self, '/\\');
		$requestUri = preg_replace("/^$pattern/", '', $requestPath);
		$this->requestUri = rawurldecode($requestUri);
	}
	
	
	/**
	 * 获取实例
	 */
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
	
	/**
	 * 获取配置
	 */
	public function getConfig($file = null)
	{
		if (!$file) {
			$file = $this->configFile;
		}
		$this->configFile = $file;
		$config = include $file;
		$this->config = $config + $this->config;
		return $this->config;
	}
	
	/*
	----------------------------------------
	| 路由
	----------------------------------------
	*/
	
	/**
	 * 获取路由信息
	 */
	public function routeInfo()
	{
		return $this->routeInfo = $this->router()->dispatch($this->requestMethod, strtolower($this->requestUri));
	}
	
	/**
	 * 获取路由器
	 */
	public function router()
	{
		return $this->router = \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) {
			$GLOBALS['PHP']->addRoute($r);
		});
	}
	
	/**
	 * 添加路由规则
	 */
	public function addRoute($r = null)
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
	
	/*
	-------------------------------------------
	| 调度器、控制器、模板
	-------------------------------------------
	*/
	
	/**
	 * 获取调度器
	 */
	public function dispatcher($routeInfo = [], $requestInfo = [], $controllerVars = [])
	{
		if (null !== $this->dispatcher) {
			return $this->dispatcher;
		}
		return $this->dispatcher = new Dispatcher($routeInfo, $requestInfo, $controllerVars);
	}
	
	/**
	 * 获取控制器
	 */
	public function controller($routeInfo = [], $requestInfo = [], $controllerVars = [], $last = true)
	{
		$routeInfo = $routeInfo ? : $this->routeInfo;
		$requestInfo = $requestInfo ? : ['method' => $this->requestMethod, 'uri' => $this->requestUri];
		
		if ($last && null !== $this->controller) {
			return $this->controller;
		}
		$dispatcher = $this->dispatcher();
		$uniqueId = $dispatcher->initialization($routeInfo, $requestInfo, $controllerVars);
		return $this->controller = $dispatcher->getController();
	}
	
	/**
	 * 模板引擎
	 */
	public function template()
	{
		# $tpl =& $this->template;
		$tpl =& self::$templet;
		if (null != $tpl) {
			return $tpl;
		}
		return $this->template = $tpl = new \League\Plates\Engine(APP_PATH . '/template');
	}
	
	/*
	-------------------------------------------
	| 内置函数
	-------------------------------------------
	*/
	
	
	
	/**
	 * 获取唯一标识
	 */
	public static function getUniqueId($routeInfo = [], $requestInfo = [], $controllerVars = [])
	{
		$req = md5(json_encode($requestInfo));
		$var = md5(json_encode($controllerVars));
		return $unique = Core::_var($routeInfo, 0, '') . '_' . Core::_var($routeInfo, 1, '') . '_' . $req . '_' . $var;
	}
	
	
	
	/**
	 * 统计信息
	 *
	 */
	public function getLog()
	{
		$runtime = microtime(true) - START_TIME;
		return $LOG = [
			'runtime' => round($runtime, 3),
			'reqs' => number_format(1 / $runtime, 2),
			'memory_use' => number_format((memory_get_usage() - START_MEM) / 1024, 3),
			'file_load' => count(get_included_files()),
		];
	}
	
	/**
	 * 析构函数
	 *
	 * 匹配路由并调用控制器
	 */
	public function __destruct()
	{
		$GLOBALS['PHP'] = $this;
		if ($this->config['route']) {
			$routeInfo = $this->routeInfo(); # 
		}
		
		$controller = $this->controller();
		if (!$controller->exec && 2 == $controller->destruct) {
			$var = $controller->_run(null, null, 0);
			print_r($var);
		}
	}
}
