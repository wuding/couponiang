<?php
namespace app\helper;

use app\model\AlimamaProductCategory;
use app\model\AlimamaChoiceList;

class Item extends \Astro\Core
{
	/**
	 * 获取商品列表
	 *
	 */
	public function list($limit = 10, $Category = null)
	{
		/* 定义 */
		$query = trim($this->_get('q'));
		$category_id = $this->_get('category');
		$site_id = $this->_get('site');
		$price = $this->_get('price');
		$save = $this->_get('save');
		$start_time = trim($this->_get('start'));
		$end_time = trim($this->_get('end'));
		$sold = $this->_get('sale');
		$sort = $this->_get('sort');
		$order = $this->_get('order');
		$page = $this->_get('page', 1);
		$List = new AlimamaChoiceList;
		if (null === $Category) {
			$Category = new AlimamaProductCategory;
		}
		$items = [];
		
		/* 商品 */		
		$cats = $List->whereCategory($category_id, $Category); // 分类		
		$List->whereQuery($query); // 关键词		
		$List->whereSite($site_id); // 网站		
		$List->wherePrice($price); // 优惠价		
		$List->whereSave($save); // 省钱		
		$List->whereStart($start_time); // 开始		
		$List->whereEnd($end_time); // 结束
		$where = $List->whereSale($sold); // 月销
		
		// 计算
		$count = $List->count($List->table_name, $where);
		$pages = ceil($count / $limit);
				
		// 结果集
		if ($count) {			
			$order_by = $List->orderBy($sort, $order); // 排序
			
			$page = ($page > $pages) ? $pages : $page;
			$offset = $page * $limit - $limit;
			$items = $List->items($where, $order_by, $limit, $offset);
		}		
		return get_defined_vars();
	}
}
