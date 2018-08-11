<?php
define('APP_PATH', dirname(__DIR__) . '/app');
date_default_timezone_set('Asia/Shanghai');

/**
 * develop 	错误报告、调试信息
 * test		时间、内存
 * product 	日志
 *
 */
require APP_PATH . '/../src/Astro/console.php';
Wu::php();
