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
		$result = null;
		$signature = isset($_SERVER['HTTP_X_HUB_SIGNATURE']) ? $_SERVER['HTTP_X_HUB_SIGNATURE'] : '';
		$github_event = isset($_SERVER['HTTP_X_GITHUB_EVENT']) ? $_SERVER['HTTP_X_GITHUB_EVENT'] : '';
		$php_input = file_get_contents('php://input'); # 
		$payload = $_POST['payload'];
		
		$json = json_decode($payload);		
		$hash = "sha1=" . hash_hmac('sha1', $php_input, $secret);
		$cmp = strcmp($signature, $hash);
		
		
		switch ($github_event) {
			case 'push':
				$result = $this->git_pull();
				break;
			default:
				break;
		}
		
		$path = 'log/';
		$put = file_put_contents($path . time() . '.txt', print_r($GLOBALS, true));
		$log = file_put_contents($path . date('nj') . '.log', print_r($json, true) . PHP_EOL, FILE_APPEND);
		$this->_json(0, 'test', get_defined_vars());
	}
	
	/**
	 * 拉取
	 */
	public function git_pull()
	{
		return __LINE__;
	}
}
