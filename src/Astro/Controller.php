<?php
namespace Astro;

class Controller extends Core
{
	/* 参数 */
	public $methods = null;	 //所有的方法名称
	
	/* 变量 */
	public $requestMethod = null; //请求的HTTP方式	
	public $requestAction = null; //请求的动作名称
	public $restfulAction = null; //请求的HTTP动作名称
	public $actionRun = null; //最终执行的动作名称	
	public $exec = 0;
	public $disableView = false;
	
	/* 配置 */
	public $action = 'index'; //缺省的动作名称
	public $restfulMethods = [
		'_action' => [
			'get' => '_get_',
			'post' => '_post_',
			'put' => '_put_',
			'delete' => '_delete_',
		],
	]; //方法映射配置
	public $destruct = 2; //析构函数执行后续动作 1自动 2手动
	public $exit = null; //执行后退出
	
	/**
	 * 构造函数
	 *
	 * 传入动作名、HTTP方法和控制器变量
	 */
	public function __construct($action = '', $method = '', $vars = [])
	{
		// 变量
		# print_r($vars);
		if ($vars) {
			foreach ($vars as $key => $value) {
				$this->$key = $value;
			}
		}
		
		// 请求
		$this->requestMethod = strtolower($method ? : $_SERVER['REQUEST_METHOD']);
		
		// 动作
		$this->requestAction = $action = $action ? : $GLOBALS['PHP']->dispatcher->actionName;		
		$this->methods = $methods = get_class_methods($this);
		$action = $action ? : $this->action;
		if ($action) {
			// 检测方法是否存在			
			$this->restfulAction = $restfulAction = '_' . $this->requestMethod . '_' . $action;
			if (in_array($restfulAction, $methods)) {
				$action = $restfulAction;
			}
		}
		$this->action = $action;
		
		// 执行自定义初始化
		$this->_init();
	}
	
	
	
	/**
	 * 默认404动作
	 */
	public function _notfound()
	{
		return [__METHOD__, __LINE__, __FILE__];
	}
	
	/**
	 * 转接动作
	 *
	 * 主动作执行后才可用
	 */
	public function _forward($routeInfo = [], $requestInfo = [], $options = [], $exec = 2)
	{
		global $PHP;		
		$opt = ['vars' => null, 'return' => null, 'exit' => null];
		$options = array_merge($opt, $options);
		
		$var = null;
		$controller = $PHP->controller($routeInfo, $requestInfo, $options['vars'], 0);
		if (2 == $exec) {					
			$var = $controller->_run($options['return'], $options['exit']);
		}
					
		if ($options['return']) {
			return $var;
		}
	}
	
	
	/**
	 * 运行动作方法
	 *
	 * 然后返回数据或输出结果
	 */
	public function _run($return = null, $exit = null, $exec = null)
	{
		$php = $GLOBALS['PHP'];
		$method = $this->requestMethod;
		$action = $this->action;
		$maps = $this->restfulMethods;
		
		
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
		$var = $GLOBALS['PHP']->config['view'];
		if ($destructs && ($exec || $this->destruct)) {
			$var += $this->$action();
		}
		
		# $var += ;
		
		/* 后续动作 */
		
		// 返回
		if ($return) {
			return $var;
		}
		
		// 输出
		$theme = 'aero';
		$folder = '';
		$controller = $php->dispatcher->controllerName ? : 'index';
		$path = '/index';
		if (Core::_isMobile()) {
			$path .= '.mobi'; # 
		}
		$script = $theme . '/' . $folder . $controller . $path;
		
		if (!$this->disableView) { //渲染页面
			$var = is_array($var) ? $var : [];
			echo $html = $php->template()->render($script, $var);
		} elseif ('info' === $this->disableView) {
			print_r([$exit, $var, __METHOD__, __LINE__, __FILE__]);
		}
		
		// 退出
		if (null === $exit) {
			$exit = $this->exit;
		}
		if (null !== $exit) {
			exit($exit);
		}
	}
	
	/**
	 * 析构函数
	 *
	 * 调用动作函数
	 */
	public function __destruct()
	{
		if ($GLOBALS['_SETTING']['destruct'] && 1 == $this->destruct) {
			$this->_run(null, null, 0);
		}
	}
}
