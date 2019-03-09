<?php

defined('APP_PATH') OR define('APP_PATH', dirname(__DIR__) . '/app');

require 'console.php';

class Cancer
{
    public static function php($config = null, $exec = true, $mode = null)
    {
        global $_DEBUG, $Cancer;
        $config = $config ? : APP_PATH . '/config.php';

        switch ($mode) {
            case 1:
                $Cancer = \Astro\Php::getInst($config, $exec);
                break;

            case 2:
                $Cancer = new \Astro\Php();
                $Cancer->getInstance()->restart($config, $exec);
                break;
            
            default:
                new \Astro\Php($config, $exec);
                break;
        }

        if ($_DEBUG['enable'] && null !== $_DEBUG['last']) {
            include 'timeline.php';
        }
    }
}
