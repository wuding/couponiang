<?php
namespace app\module\api\controller;

class Git extends _Abstract
{
	public $disableView = true; # 
	
	/**
	 * 钩子
	 *
	 */
	public function hooks()
	{
		header('Content-Type: application/json');
		$secret = '';
		$signature = isset($_SERVER['HTTP_X_HUB_SIGNATURE']) ? $_SERVER['HTTP_X_HUB_SIGNATURE'] : '';
		$input = file_get_contents('php://input');
		$json = json_decode($input);
		
		$hash = "sha1=" . hash_hmac('sha1', $input, $secret);
		$cmp = strcmp($signature, $hash);
		
		$path = 'log/';
		$put = file_put_contents($path . time() . '.txt', print_r($GLOBALS, true));
		$log = file_put_contents($path . date('nj') . '.log', $input, FILE_APPEND);
		$this->_json(0, 'test', get_defined_vars());
	}
}
