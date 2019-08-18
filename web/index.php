<?php

$_CONFIG = require __DIR__ . '/../app/config.php';

if ('anfora' == $_CONFIG['autoload']) {
    $autoload = require dirname(__DIR__) . '/vendor/wuding/anfora/src/autoload.php';
    # include "E:/www/work/netjoin/astrology/src/autoload.php";
    # include "E:\www\work\wuding\anfora\src/autoload.php";
    $anfora = new \Anfora;

    // 依赖函数
    func($_CONFIG['func']['config'], $_CONFIG ['func']['load']);

} else {
    $Composer = require dirname(__DIR__) . '/vendor/autoload.php';
}

Cancer::php();
# print_r(get_included_files());
