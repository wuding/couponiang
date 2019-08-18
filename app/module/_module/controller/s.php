<?php

namespace app\module\_module\controller;

use OpenSearch\Generate;

class S extends _Abstract
{
    public $restfulMethods = [
        '_controller' => [
            'get' => 'index',
            'post' => '_post_',
            'put' => '_put_',
            'delete' => '_delete_',
        ],
    ]; //方法映射配置

    public function __construct($moduleInfo, $method = null, array $vars = [])
    {
        $moduleInfo['last']['action'] = $moduleInfo[0]['controller'];
        # $this->moduleInfo = $moduleInfo['last'];
        parent::__construct($moduleInfo, $method, $vars);
    }

    public function index()
    {
        $this->disableView = 'echo';
        $this->mobileSuffix = '';
        $this->responseHeaders = ['Content-Type: text/html; charset=UTF-8'];
        # echo $_GET['q'];
        $url = parse_url($_GET['q']);
        
        
        extract($url);
        $q = explode('&', $query);
        parse_str($query ,$str);
        $cmd = '';
        foreach ($q as $cmd_val) {
            if (preg_match('/^=/', $cmd_val)) {
                $cmd = $cmd_val;
                break;
            }
        }
        $qs = http_build_query($str);
        $qs = $cmd ? $cmd . '&' . $qs : $qs;
        # print_r(get_defined_vars());
        $xml = 'http://localhost.urlnk.com/' . urlencode($path) . '?' . $qs;
        header("Location: $xml");
        return false;
    }

    public function add()
    {
        return [];
    }
}
