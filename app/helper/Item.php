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
		$List = new AlimamaChoiceList;
		if (null === $Category) {
			$Category = new AlimamaProductCategory;
		}
		
		/* 商品 */
		$where = [];
		
		// 关键词
		$where = $List->whereQuery($query);		
		
		// 分类
		$cats = $List->whereCategory($category_id, $Category);
		
		// 网站
		$where = $List->whereSite($site_id);
		
		// 优惠价
		$where = $List->wherePrice($price);
		
		// 省钱
		$where = $List->whereSave($save);
		
		// 开始
		$where = $List->whereStart($start_time);
		
		// 结束
		$where = $List->whereEnd($end_time);
		
		// 月销
		$where = $List->whereSale($sold);
		
		// 排序
		$order_by = $List->orderBy($sort, $order);
		
		$items = $List->items($where, $order_by, $limit);
		return get_defined_vars();
	}
}
