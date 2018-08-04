<?php
namespace app\controller;

use app\model\AlimamaProductCategory;
use app\model\AlimamaChoiceExcel;

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
		$Category = new AlimamaProductCategory;
		$Excel = new AlimamaChoiceExcel;
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
		// 优惠价
		if ($price) {
			switch ($price) {
				case '9.9':
					$where['cost[<]'] = 10;
					break;
				case '20':
					$where['cost[<>]'] = [10, 20];
					break;
				case '50':
					$where['cost[<>]'] = [20, 50];
					break;
				case '100':
					$where['cost[<>]'] = [50, 100];
					break;
				default:
					$where['cost[<=]'] = $price;
			}
		}
		
		// 省钱
		if ($save) {
			switch ($save) {
				case '10':
					$where['discount[<>]'] = [10, 19.99];
					break;
				case '20':
					$where['discount[<>]'] = [20, 49.99];
					break;
				case '50':
					$where['discount[<>]'] = [50, 99.99];
					break;
				case '100':
					$where['discount[>='] = 100;
					break;
				default:
					$where['discount[>=]'] = $save;
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
			$where['end[<]'] = $now;
		}
		
		// 网站
		if ($site_id) {
			if (in_array($site_id, $sites)) {
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
					$where['sale[<>]'] = [100, 499];
					break;
				case '500':
					$where['sale[<>]'] = [500, 999];
					break;
				case '1000':
					$where['sale[<>]'] = [1000, 4999];
					break;
				default:
					$where['sale[>=]'] = $sold;
			}
		}
		
		// 排序
		$sort_by = $sort ? : '';
		$sorts = [
			'' => 'excel_id',
			'price' => 'cost',
			'save' => 'discount',
			'start' => 'start',
			'end' => 'end',
			'sale' => 'sale',
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
		return [
			'cat' => $tree, 
			'items' => $items, 
			'price' => $price,
			'save' => $save,
			'category_id' => $category_id, 
			'subclass' => $subclass,
			'subclass_id' => $subclass_id,
			'start_time' => $start_time,
			'end_time' => $end_time,
			'site_id' => $site_id,
			'sold' => $sold,
			'view' => $view,
			'sort' => $sort,
			'order' => $order,
			'group' => $group,
		];
		# [$all, __METHOD__, __LINE__, __FILE__];
	}
}
