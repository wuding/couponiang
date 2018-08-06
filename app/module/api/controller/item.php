<?php
namespace app\module\api\controller;

use app\model\AlimamaProductCategory;
use app\model\AlimamaChoiceExcel;

class Item extends _Abstract
{
	public $disableView = 'info';
	
	public function index()
	{
		return [__METHOD__, __LINE__, __FILE__];
	}
	
	public function _get_list()
	{
		$query = trim($this->_get('q'));
		$category_id = $this->_get('category');
		$Excel = new AlimamaChoiceExcel;
		
		$where = [];
		$order_by = null;
		
		// 关键词
		if ($query) {
			$where['name[~]'] = $query;
		}
		
		$items = $Excel->items($where, $order_by, 5);
		unset($Excel);
		$this->_json(0, '', $items);
	}
}
