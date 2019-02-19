<?php
namespace app\module\_module\controller;

use OpenSearch\Generate;

class Opensearch extends _Abstract
{
    public $disableView = 'echo';
    public $mobileSuffix = '';
    public $responseHeaders = ['Content-Type: text/xml; charset=UTF-8'];

    public $restfulMethods = [
        '_controller' => [
            'get' => 'index',
            'post' => '_post_',
            'put' => '_put_',
            'delete' => '_delete_',
        ],
    ]; //方法映射配置

    public function index()
    {
        $config = include APP_PATH . '/config/opensearch.php';
        $generate = new Generate($config);
        return $xml = $generate->xml();
        return [$config, __METHOD__, __LINE__, __FILE__];
    }
}
