<?php
namespace app\controller;

use app\model\AlimamaProductCategory;
use app\model\AlimamaChoiceExcel;

class Index extends _Abstract
{
	public function index()
	{
		/* 定义 */
		$category_id = $this->_get('category');		
		$Category = new AlimamaProductCategory;
		$Excel = new AlimamaChoiceExcel;
		
		/* 分类 */
		$class = $Category->rootIds();
		$tree = $Category->tree($class);
		$keys = array_keys($tree);
		# print_r([$keys, $tree]); 
		
		/* 商品 */
		$where = [];
		if ($category_id) {
			if (in_array($category_id, $keys)) {
				$category_id = $Category->subIds($category_id, $tree);
				# print_r($category_id);exit;
			}
			$where['category_id'] = $category_id;
		}
		$items = $Excel->items($where);
		# print_r([$class, $tree, $items]);
		return ['cat' => $tree, 'items' => $items];
		# [$all, __METHOD__, __LINE__, __FILE__];
	}
}
