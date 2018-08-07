<?php
namespace app\controller;

use app\helper\Item;
use app\model\AlimamaProductCategory;

class Index extends _Abstract
{
	public function index()
	{
		/* 定义 */
		$Item = new Item;
		$Category = new AlimamaProductCategory;
		
		/* 分类 */
		$class = $Category->rootIds();
		$tree = $Category->tree($class);
		
		/* 商品 */
		extract($Item->list(40, $Category));
		list($category_id, $subclass_id) = $cats;
		$subclass = $Category->subclass($category_id);
		
		return get_defined_vars();
	}
}
