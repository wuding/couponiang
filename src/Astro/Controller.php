<?php
namespace Astro;

class Controller extends Core
{
	/* 参数 */
	public $methods = null;	 //所有的方法名称
	
	/* 变量 */
	public $httpMethod = null; //请求的HTTP方式	
	public $actionName = null; //请求的方法名称
	public $actionMethod = null; //请求的HTTP方法名称
	public $actionRun = null; //最终执行的方法名称	
	public $exec = 0;
	public $disableView = false;
	
	/* 配置 */
	public $action = 'index'; //缺省的方法名称	
	public $actionMethods = [
		'_action' => [
			'get' => '_get_',
			'post' => '_post_',
			'put' => '_put_',
			'delete' => '_delete_',
		],
	]; //方法映射配置
	public $destruct = 2; //析构函数执行后续动作 1自动 2手动
	public $exit = null; //执行后退出# 
	
	public function __construct($action = '', $method = '', $vars = [])
	{
		# print_r($vars);
		if ($vars) {
			foreach ($vars as $key => $value) {
				$this->$key = $value;
			}
		}
		
		$this->httpMethod = strtolower($method ? : $_SERVER['REQUEST_METHOD']);
		$this->actionName = $action = $action ? : $GLOBALS['PHP']->dispatcher->actionName;
		
		
		/* 检测方法是否存在 */
		$this->methods = $methods = get_class_methods($this);
		$action = $action ? : $this->action;
		if ($action) {			
			$this->actionMethod = $actionMethod = '_' . $this->httpMethod . '_' . $action;
			if (in_array($actionMethod, $methods)) {
				$action = $actionMethod;
			}
		}
		$this->action = $action;
		# print_r([__METHOD__, __LINE__, __FILE__]);
		$this->_init();
	}
	
	public function _init()
	{
	}
	
	public function _notfound()
	{
		return [__METHOD__, __LINE__, __FILE__];
	}
	
	/**
	 *
	 * 主动作执行后才可用
	 */
	public function _forward($routeInfo = [], $requestInfo = [], $options = [], $destruct = 2)
	{
		global $PHP;# 
		# $PHP = $PHP ? : $GLOBALS['PHP'];
		# var_dump($PHP);
		# exit;
		
		$opt = ['vars' => null, 'return' => null, 'exit' => null];
		$options = array_merge($opt, $options);
		
		$var = null;
		# $dispatcher = $dispatcher->getController();
		$controller = $PHP->controller($routeInfo, $requestInfo, $options['vars'], 0);
		if (2 == $destruct) {					
			$var = $controller->_destruct($options['return'], $options['exit']);
		}
					
		if ($options['return']) {
			return $var;
		}
	}
	
	
	
	public function _destruct($return = null, $exit = null, $destruct = null)
	{
		# print_r([$return, $exit, $destruct, __METHOD__, __LINE__, __FILE__]);
		$php = $GLOBALS['PHP'];
		# $template = $php->template();
		# print_r($php);
		$method = $this->httpMethod;
		$action = $this->action;
		$maps = $this->actionMethods;
		
		
		/* 方法映射 */
		if (isset($maps[$action]) && $map = $maps[$action]) {
			if (isset($map[$method]) && $name = $map[$method]) {
				$action = $name;
			}
		}
		if (!in_array($action, $this->methods)) {
			$action = '_notfound';
		}
		$this->actionRun = $action;
		
		/* 循环执行控制 */
		$destructs = 1;
		if (1 < $this->exec) {
			# print_r($this);exit;
			$return = 1;
			$exit = 'ty';
			$destructs = 0;
		}
		
		/* 执行方法 */
		$this->exec++;
		$var = null;
		if ($destructs && ($destruct || $this->destruct)) {
			$var = $this->$action();
		}
		
		/* 后续动作 */
		// 返回
		if ($return) {
			return $var;
		}
		
		
		
		// 输出
		# print_r([$exit, $var, __METHOD__, __LINE__, __FILE__]);exit; 
		$theme = 'aero';
		$folder = '';
		$controller = $php->dispatcher->controllerName ? : 'index';
		$path = '/index';
		$script = $theme . '/' . $folder . $controller . $path;
		/* 渲染页面 */
		if (!$this->disableView) {
			$var = is_array($var) ? $var : [];
			echo $html = $php->template()->render($script, $var);
		} elseif ('info' == $this->disableView) {
			print_r([$exit, $var, __METHOD__, __LINE__, __FILE__]);
		}
		
		// 退出
		if (null === $exit) {
			$exit = $this->exit; #@
		}
		if (null !== $exit) {
			exit($exit);
		}
	}
	
	/*
	public function setExit($exit = null)
	{
		$this->exit = $exit;
	}
	*/
	
	public function __destruct()
	{
		if ($GLOBALS['_CONTROLLER']['destruct'] && 1 == $this->destruct) {
			$this->_destruct(null, null, 0);
		}
		# print_r([__METHOD__, __LINE__, __FILE__]);# 
	}
}
