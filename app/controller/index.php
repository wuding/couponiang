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
		
		// 请求地址
		$request_uri = $_SERVER['REQUEST_URI'];
		$URL = parse_url($request_uri);
		$request_path = $URL['path'];
		parse_str($_SERVER['QUERY_STRING'], $QUERY);
		unset($QUERY['nsukey']);
		$query_string = http_build_query($QUERY);		
		$source_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $request_path;
		if ($query_string) {
			$source_url .= '?' . $query_string;
		}
		
		// 设备检测
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
