<?php
namespace app\module\api\controller;

use app\model\AlimamaProductCategory;

class Category extends _Abstract
{
	public $disableView = 'info2';
	
	public function index()
	{
		return [__METHOD__, __LINE__, __FILE__];
	}
	
	/**
	 * 获取商品列表
	 *
	 */
	public function _get_subclass()
	{
		/* 定义 */
		$category_id = $this->_get('cid');
		$Category = new AlimamaProductCategory;
		
		/* 商品 */
		$all = $Category->subClasses($category_id);
		# print_r($subIds);
		
		# $all = [['category_id' => '', 'title' => '子类']] + $all;
		$data = ['cid' => $category_id, 'list' => $all];
		$code = 0;
		$msg = '';
		$this->_json($code, $msg, $data);
	}
}
