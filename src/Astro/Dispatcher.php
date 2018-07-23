<?php
namespace Astro;

class Dispatcher
{
	// 参数
	public $routeInfo = [];
	public $requestInfo = [];
	public $controllerVars = [];
	
	// 对象
	public $controller = null;
	public $controllers = [];
	
	// 变量
	public $moduleInfo = [];
	
	public function __construct($routeInfo = [], $requestInfo = [], $controllerVars = [])
	{
		$this->init($routeInfo, $requestInfo, $controllerVars);
	}
	
	/**
	 * 初始化参数
	 */
	public function init($routeInfo = [], $requestInfo = [], $controllerVars = [])
	{
		$this->routeInfo = $routeInfo;
		$this->requestInfo = $requestInfo;
		$this->controllerVars = $controllerVars;
		return $this->uniqueId = Php::getUniqueId($routeInfo, $requestInfo, $controllerVars);
	}
	
	/**
	 * 获取控制器对象
	 *
	 */
	public function getController($routeInfo = [], $requestInfo = [], $controllerVars = [])
	{
		$routeInfo = $routeInfo ? : $this->routeInfo;
		$requestInfo = $requestInfo ? : $this->requestInfo;
		$controllerVars = $controllerVars ? : $this->controllerVars;
		
		$this->controllerUniqueId = Php::getUniqueId($routeInfo, $requestInfo, $controllerVars);
		if (isset($this->controllers[$this->controllerUniqueId])) {
			return $this->controllers[$this->controllerUniqueId];
		}
		return $this->dispatchController($routeInfo, $requestInfo);
	}
	
	/**
	 * 调出控制器对象
	 *
	 */
	public function dispatchController($routeInfo = [], $requestInfo = [], $controllerVars = [])
	{
		$routeInfo = $routeInfo ? : $this->routeInfo;
		$requestInfo = $requestInfo ? : $this->requestInfo;
		$controllerVars = $controllerVars ? : $this->controllerVars;
		
		$this->controllerUniqueId = Php::getUniqueId($routeInfo, $requestInfo, $controllerVars);
		
		$class = $this->getControllerClassName($routeInfo, $requestInfo);
		if (!class_exists($class)) {
			$this->moduleInfo[$class]['exist'] = -1;
			$routeInfo = [0];
			$class = $this->getControllerClassName($routeInfo, $requestInfo);
		}
		return $this->controller = $this->controllers[$this->controllerUniqueId] = new $class($this->actionName, $requestInfo['method'], $controllerVars);
	}
	
	/**
	 * 获取控制器类名
	 *
	 */
	public function getControllerClassName($routeInfo = [], $requestInfo = [])
	{
		$routeInfo = $routeInfo ? : $this->routeInfo;
		$requestInfo = $requestInfo ? : $this->requestInfo;
		
		$handler = '_module/_controller/_action';
		$status = $routeInfo[0];		
		switch ($status) {
			case 1:
				$handler = $routeInfo[1];
				$vars = $routeInfo[2];
				break;
			case 0:
				$handler = '_module/_controller/_notfound';
				break;
			case 2:
				$allowedMethods = $routeInfo[1];
				break;
		}
		
		$pathInfo = explode('/', $handler);
		$this->moduleName = $module = Php::getArrayVar($pathInfo, 0, '_module');
		$this->controllerName = $controller = Php::getArrayVar($pathInfo, 1, '_controller');
		$this->actionName = $action = Php::getArrayVar($pathInfo, 2, '_action');
		
		$namespace = $this->getControllerNamespace($routeInfo, $requestInfo);
		$class = $namespace . $controller;
		$this->moduleInfo[$class] = [$module, $controller, $action, $handler, $routeInfo, 'exist' => 0];
		return $this->controllerClass = $class;
	}
	
	/**
	 * 获取控制器命名空间
	 *
	 */
	public function getControllerNamespace($requestInfo = [], $moduleInfo = [], $options = [])
	{
		return $this->controllerNamespace = "\\app\\controller\\";
	}
	
	public function __destruct()
	{
	}
}
	