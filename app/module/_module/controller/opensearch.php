<?php
namespace app\module\_module\controller;

use OpenSearch\Generate;

class Opensearch extends _Abstract
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
        $this->responseHeaders = ['Content-Type: text/xml; charset=UTF-8'];
        $config = include APP_PATH . '/config/opensearch.php';
        $generate = new Generate($config);
        return $xml = $generate->xml();
    }

    public function add()
    {
        return [];
    }
}
