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
	public $controllerName = null;
	public $controllerNS = null;
	public $moduleDefault = 'index';
	public $controllerDefault = 'index';
	public $actionDefault = 'index';
	
	/**
	 * 构造函数
	 *
	 * 调用初始化函数
	 */
	public function __construct($routeInfo = [], $requestInfo = [], $controllerVars = [])
	{
		$this->initialization($routeInfo, $requestInfo, $controllerVars);
	}
	
	/**
	 * 初始化参数
	 */
	public function initialization($routeInfo = [], $requestInfo = [], $controllerVars = [])
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
		
		$this->controllerId = Php::getUniqueId($routeInfo, $requestInfo, $controllerVars);
		if (isset($this->controllers[$this->controllerId])) {
			return $this->controllers[$this->controllerId];
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
		
		$this->controllerId = Php::getUniqueId($routeInfo, $requestInfo, $controllerVars);
		
		// 匹配的
		$class = $this->getControllerClassName($routeInfo, $requestInfo);
		if (!class_exists($class)) {
			$this->moduleInfo[$class]['exist'] = -1;
			$class_exists = 0;
			$moduleName = $this->moduleName;
			// 调用这个模块的缺省控制器
			if ('_module' != $moduleName && !is_numeric($moduleName)) {
				$handler = $moduleName . '/_controller/' . $this->actionName;
				$routeInfo = [1, $handler, []];
				$class = $this->getControllerClassName($routeInfo, $requestInfo);
				$class_exists = class_exists($class);
				if (!$class_exists) {
					$this->moduleInfo[$class]['exist'] = -1;

					// 模块作为控制器
					$handler = '_module/' . $moduleName . '/' . $this->controllerName;
					$routeInfo = [1, $handler, []];
					$class = $this->getControllerClassName($routeInfo, $requestInfo);
					$class_exists = class_exists($class);
					if (!$class_exists) {
						$this->moduleInfo[$class]['exist'] = -1;

						// 模块作为动作
						$handler = '_module/_controller/' . $moduleName;
						$routeInfo = [1, $handler, []];
						$class = $this->getControllerClassName($routeInfo, $requestInfo);
						$class_exists = class_exists($class);
						if (!$class_exists) {
							$this->moduleInfo[$class]['exist'] = -1;
						}
					}
				}
			}

			// 缺省模块
			if (!$class_exists) {
				$routeInfo = [0];
				$class = $this->getControllerClassName($routeInfo, $requestInfo, 1);
			}
		}
		return $this->controller = $this->controllers[$this->controllerId] = new $class($this->actionName, $requestInfo['method'], $controllerVars);
	}
	
	/**
	 * 获取控制器类名
	 *
	 */
	public function getControllerClassName($routeInfo = [], $requestInfo = [], $notFound = null)
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
				$handler = $notFound ? '_module/_controller/_action' : trim($requestInfo['uri'], '/');
				break;
			case 2:
				$allowedMethods = $routeInfo[1];
				break;
		}
		
		// 忽略的后缀
		$handler = preg_replace('/\.(php|html)$/i', '', $handler);
		
		// 分割信息
		$pathInfo = explode('/', $handler);
		$module = Core::_var($pathInfo, 0);
		$controller = Core::_var($pathInfo, 1);
		$action = Core::_var($pathInfo, 2);
		
		// 校验		
		if (preg_match('/^[a-z_]/i', $module) && preg_match('/^([a-z_0-9]+)$/i', $module)) {
		} else {
			$module = $this->moduleDefault ? : '_module';
		}
		if (preg_match('/^[a-z_]/i', $controller) && preg_match('/^([a-z_0-9]+)$/i', $controller)) {
		} else {
			$controller = $this->controllerDefault ? : '_controller';
		}
		if (preg_match('/^[a-z_]/i', $action) && preg_match('/^([a-z_0-9]+)$/i', $action)) {
		} else {
			$action = $this->actionDefault ? : '_action';
		}
		
		$this->moduleName = $module;
		$this->controllerName = $controller;
		$this->actionName = $action;
		
		$moduleInfo = [$module, $controller, $action, $handler, $routeInfo, 'exist' => 0];
		$namespace = $this->getControllerNamespace($moduleInfo, $requestInfo);
		$class = $namespace . $controller;
		
		$this->moduleInfo[$class] = $moduleInfo;
		return $this->controllerClass = $class;
	}
	
	/**
	 * 获取控制器命名空间
	 *
	 */
	public function getControllerNamespace($moduleInfo = [], $requestInfo = [], $options = [])
	{
		$moduleFolder = '';
		if ('index' != $moduleInfo[0]) {
			$moduleFolder = '\\module\\' . $moduleInfo[0];
		}
		return $this->controllerNS = "\\app$moduleFolder\\controller\\";
	}
}
	