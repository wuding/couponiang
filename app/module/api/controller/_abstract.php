<?php
namespace app\module\api\controller;

class _Abstract extends \Astro\Controller
{
	public function _action()
	{
		print_r([__METHOD__, __LINE__, __FILE__]);
	}
	
	public function _json($code = 0, $msg = '', $data = [])
	{
		$arr = [
			'code' => $code,
			'msg' => $msg,
			'data' => $data,
		];
		echo $json = json_encode($arr);
		exit;
	}
}
