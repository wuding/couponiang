<?php
namespace app\module\api\controller;

use app\helper\Item as HelperItem;

class Item extends _Abstract
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
	public function _get_list()
	{
		/* 定义 */
		$Item = new HelperItem;
		
		/* 商品 */
		extract($Item->lists(40));
		unset($List, $Category);
		
		$code = (int) $overflow;
		$msg = '';
		if (1 == $code) {
			$msg = '没有更多了';
		}
		$this->_json($code, $msg, $items);
	}
}
