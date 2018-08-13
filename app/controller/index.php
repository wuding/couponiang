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
		$request_uri = htmlspecialchars($_SERVER['REQUEST_URI']);
		$source_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $request_uri;
		$uaStr = '';
		$UA = \Astro\Core::_userAgent($uaStr);
		$wx = \Astro\Core::_isMobile('/MicroMessenger/i', $uaStr);
		
		/* 分类 */
		$class = $Category->rootIds();
		$tree = $Category->tree($class);
		
		/* 商品 */
		extract($Item->lists($limit, $Category));

		// 分类
		list($category_id, $subclass_id) = $cats;
		$subclass = $Category->subclass($category_id);

		// 分页
		$pagination = ViewItem::paginator($count, $page, $limit);
		return get_defined_vars();
	}
}
