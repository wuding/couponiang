<?php
namespace app\controller;

use app\helper\Item;
use app\model\AlimamaProductCategory;
use app\view\Item as ViewItem;

class Index extends _Abstract
{
	public function index()
	{
		/* 定义 */
		$Item = new Item;
		$Category = new AlimamaProductCategory;
		$limit = 40;
		$stat = $this->stat;
		
		/* 分类 */
		$class = $Category->rootIds();
		$tree = $Category->tree($class);
		
		/* 商品 */
		extract($Item->list($limit, $Category));

		// 分类
		list($category_id, $subclass_id) = $cats;
		$subclass = $Category->subclass($category_id);

		// 分页
		$pagination = ViewItem::paginator($count, $page, $limit);
		return get_defined_vars();
	}
}
