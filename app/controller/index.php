<?php
namespace app\controller;

class Index extends _Abstract
{
	public function index()
	{
		$AlimamaProductCategory = new \app\model\AlimamaProductCategory;
		$all = $AlimamaProductCategory->rootIds();
		return ['cat' => $all];
		# [$all, __METHOD__, __LINE__, __FILE__];
	}
}
