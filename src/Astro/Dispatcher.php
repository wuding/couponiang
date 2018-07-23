<?php
namespace Astro;

class Dispatcher
{
	// 参数
	public $routeInfo = [];
	public $requestInfo = [];
	
	// 对象
	public $controller = null;
	
	// 变量
	public $moduleInfo = [];
	
	public function __construct($routeInfo = [], $requestInfo = [])
	{
		$this->routeInfo = $routeInfo;
		$this->requestInfo = $requestInfo;
	}
	
	/**
	 * 获取控制器对象
	 *
	 */
	public function getController($routeInfo = [], $requestInfo = [])
	{
		if (null == $this->controller) {
			return $this->dispatchController($routeInfo, $requestInfo);
		}
		return $this->controller;
	}
	
	/**
	 * 调出控制器对象
	 *
	 */
	public function dispatchController($routeInfo = [], $requestInfo = [])
	{
		$class = $this->getControllerClassName($routeInfo, $requestInfo);
		if (!class_exists($class)) {
			$this->moduleInfo[$class]['exist'] = -1;
			$routeInfo = [0];
			$class = $this->getControllerClassName($routeInfo, $requestInfo);
		}
		return $this->controller = new $class;
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
	