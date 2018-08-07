<?php
namespace app\controller;

use app\model\AlimamaProductCategory;
use app\model\AlimamaChoiceList;

class Index extends _Abstract
{
	public function index()
	{
		/* 定义 */		
		$price = $this->_get('price');
		$save = $this->_get('save');
		$start_time = trim($this->_get('start'));
		$end_time = trim($this->_get('end'));
		$site_id = $this->_get('site');
		$category_id = $this->_get('category');
		$subclass_id = $this->_get('subclass');
		$sold = $this->_get('sale');
		$view = $this->_get('view');
		$sort = $this->_get('sort');
		$order = $this->_get('order');
		$group = $this->_get('group');
		$query = trim($this->_get('q'));
		$Category = new AlimamaProductCategory;
		$Excel = new AlimamaChoiceList;
		$now = date('Y-m-d H:i:s');
		$sites = [1, 2 ,3];
		$subclass = [];
		
		
		
		/* 分类 */
		$class = $Category->rootIds();
		$tree = $Category->tree($class);
		$keys = array_keys($tree);
		# print_r([$keys, $tree, $class]);exit; 
		
		/* 商品 */
		$where = [];
		// 关键词
		if ($query) {
			$where['title[~]'] = $query;
		}
		
		// 优惠价
		if ($price) {
			switch ($price) {
				case '9.9':
					$where['price[<]'] = 10;
					break;
				case '20':
					$where['price[<>]'] = [10, 20];
					break;
				case '50':
					$where['price[<>]'] = [20, 50];
					break;
				case '100':
					$where['price[<>]'] = [50, 100];
					break;
				default:
					$where['price[<=]'] = $price;
			}
		}
		
		// 省钱
		if ($save) {
			switch ($save) {
				case '10':
					$where['save[<>]'] = [10, 19.99];
					break;
				case '20':
					$where['save[<>]'] = [20, 49.99];
					break;
				case '50':
					$where['save[<>]'] = [50, 99.99];
					break;
				case '100':
					$where['save[>='] = 100;
					break;
				default:
					$where['save[>=]'] = $save;
			}
		}
		
		
		
		// 开始
		if ($start_time) {
			$start_times = $Excel->datetime($start_time);
			$start_count = count($start_times);
			if (1 < $start_count && $start_times[1]) {
				$start_times[0] = $start_times[0] ? : $now;
				$where['start[<>]'] = $start_times;
			} else {
				$where['start[>=]'] = $start_times[0];
			}
		}
		
		// 结束
		if ($end_time) {
			$end_times = $Excel->datetime($end_time);
			$end_count = count($end_times);
			if (1 < $end_count) {
				$end_times[0] = $end_times[0] ? : $now;
				if (!$end_times[1]) {
					$where['end[>=]'] = $end_times[0];
				} else {
					$where['end[<>]'] = $end_times;
				}
			} else {
				$where['end[>=]'] = $end_times[0];
			}
		} else {
			$where['end[>]'] = $now;
		}
		
		// 网站
		if ($site_id) {
			if (in_array($site_id, $sites)) {
				/*
				switch ($site_id) {
					case '1':
						$where['platform'] = '淘宝';
						break;
					case '2':
						$where['platform'] = '天猫';
						break;
					case '3':
						$where['group[>]'] = 0;
						break;
				}
				*/
				$where['site'] = $site_id;
			}
		}
		
		// 分类
		if ($category_id) {
			$where['category_id'] = $subclass_id ? : $category_id;
			if (in_array($category_id, $keys)) {
				if (!$subclass_id) {
					$where['category_id'] = $Category->subIds($category_id, $tree);
					# print_r($category_id);exit;
				}
				
			} else {
				$subclass_id = $category_id;
				$category_id = $Category->supId($category_id, $class);
			}
			
			if (isset($tree[$category_id])) {
				$subclass = $tree[$category_id]->leaves;
			}
		}
		
		// 月销
		if ($sold) {
			switch ($sold) {
				case '100':
					$where['sold[<>]'] = [100, 499];
					break;
				case '500':
					$where['sold[<>]'] = [500, 999];
					break;
				case '1000':
					$where['sold[<>]'] = [1000, 4999];
					break;
				default:
					$where['sold[>=]'] = $sold;
			}
		}
		
		// 排序
		$sort_by = $sort ? : '';
		$sorts = [
			'' => 'list_id',
			'price' => 'price',
			'save' => 'save',
			'start' => 'start',
			'end' => 'end',
			'sale' => 'sold',
		];
		$orders = [
			'' => 'DESC',
			'price' => 'ASC',
			'save' => 'DESC',
			'start' => 'DESC',
			'end' => 'ASC',
			'sale' => 'DESC',
		];
		$order_by = '';		
		if (isset($sorts[$sort_by])) {
			# print_r([$sort, $order, $sort_by, $sorts[$sort_by], $orders[$sort_by]]); 
			$orderBy = $order ? strtoupper($order): $orders[$sort_by];
			$order_by = [$sorts[$sort_by] => $orderBy];
		}
		
		$items = $Excel->items($where, $order_by, 40);
		# print_r([$class, $tree, $items]);
		$cat = $tree;
		return get_defined_vars();
	}
}
