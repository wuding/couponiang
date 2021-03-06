<?php
namespace app\model;

class AlimamaChoiceList extends \Astro\Database
{
	public $db_name = 'shopping';
	public $table_name = 'alimama_choice_list';
	public $primary_key = 'list_id';
	public $return = ['select'];
	
	public static $where = [];
	public static $attr = [];
	public static $now = null;
	public static $week = null;
	public static $DBY = null;
	
	public $columns = [
		'all' => ['excel_id', 'title', 'pic', 'price', 'save', 'sold', 'end', 'site', 'tao_token'],
		'row' => ['category_id', 'title', 'pic', 'price'],
	];
	
	/**
	 * 初始化
	 *
	 */
	public function _init()
	{
		self::$now = date('Y-m-d H:i:s');
		self::$week = date('N');
		$date = date('Y-m-d');
		$time = strtotime($date);
		self::$DBY = date('Y-m-d', $time - 172800);
		self::$attr = [
			'date' => $date,
			'time' => $time,
		];
		
		# array_unshift($this->columns['all'], $this->primary_key);
	}
	
	/**
	 * 获取产品列表
	 *
	 */
	public function items($condition = [], $sort = null, $limit = 40, $offset = 0)
	{
		$sort = $sort ? : [$this->primary_key => 'DESC'];
		
		
		/* 获取数据 */
		$where = $condition + [];		
		$option = [$sort, $limit, $offset];
		$all = $this->select($where, $this->columns['all'], $option);
		return $all;
	}
	
	#! 未使用
	public function item($condition = [], $sort = null, $limit = 1, $offset = 0)
	{
		$sort = $sort ? : [$this->primary_key => 'DESC'];
		
		$where = [];
		$where += $condition;
		
		$option = [$sort, $limit, $offset];
		return $row = $this->sel($where, $this->columns['row'], $option);
	}
	
	/**
	 * 时间条件
	 *
	 */
	public function datetime($time, $type = null)
	{
		$arr = [];
		if ($time) {
			$times = explode('_', $time);
			$count = count($times);
			if (1 < $count) {
				$arr = $times;
			} else {
				if (preg_match('/:/', $time)) {
					$arr = [null, $time];
				} else {
					$arr = [$time, $time . ' 23:59:59'];
				}
			}
		}
		return $arr;
	}
	
	/**
	 * 分类条目数 - 未使用
	 *
	 */
	public function categoryNum()
	{
		$where = [];
		$column = ['category_id', 'COUNT(0) AS num'];
		$option = ['category_id', 200];
		$all = $this->select($where, $column, $option, ['category_id']);
		print_r($all);
	}
	
	/**
	 * where
	 *
	 */
	public function setWhere($where = null)
	{
		if (null !== $where) {
			self::$where = $where;
		}
	}
	
	/**
	 * where
	 *
	 */
	public function getWhere($where = null)
	{
		if (null !== $where) {
			self::$where = $where;
		}
		return self::$where;
	}
	
	/**
	 * where - 查询
	 *
	 */
	public function whereQuery($query, $where = null)
	{
		$where = $this->getWhere($where);
		if ($query) {
			# $where['title[~]'] = $query;
			$and = [];
			$qe = preg_split("/\s+/", $query);
			
			$fill = ['REGEXP' => [], '!REGEXP' => []];
			foreach ($qe as $qr) {
				// 生成键值
				$qr = trim($qr);
				$matche = 'REGEXP';
				if (preg_match("/^-/", $qr, $matches)) {
					$qr = preg_replace("/^[-]+/", "", $qr);
					$matche = '!REGEXP';
				}
				$arr = $fill[$matche];
				# print_r([ __LINE__, $arr]);
				
				// 数组搜索包含
				$status = 0;
				foreach ($arr as &$v) {
					$a = mb_strlen($v);
					$b = mb_strlen($qr);
					$pos = null;
					if ($a == $b) {
					} elseif ($a > $b) {
						$pos = strpos($v, $qr);
					} else {
						$pos = strpos($qr, $v);
					}
					# print_r([var_dump($pos), __LINE__, $a, $b]);
					if (null !== $pos) {
						if (false !== $pos) {
							if ($a < $b) {
								$v = $qr;# 
							}
							$status++;
							# break; 
						}
					}
				}
				# print_r([ __LINE__, $arr]);
				
				// 填充数组
				if (!$status && !in_array($qr, $arr)) {
					$arr[] = $qr;
				}
				$fill[$matche] = $arr;
				# print_r([$qr, __LINE__, $fill[$matche], $arr]);
				
			}
			
			// 生成查询数组
			foreach ($fill as $key => $value) {
				foreach ($value as $val) {
					# $fill[$key][] = $val;
					$k = "title[$key]";
					if (!isset($and[$k])) {
						$and[$k] = ['AND' => []];
					}
					$and[$k]['AND'][] = $val;
				}
			}
			# print_r([$qe, $and]);
			$where['AND'] = $and;
		}
		return $this->getWhere($where);
	}
	
	/**
	 * where - 网站
	 *
	 */
	public function whereSite($site_id, $where = null)
	{
		$where = $this->getWhere($where);
		if ($site_id) {
			$sites = [1, 2 ,3];
			if (in_array($site_id, $sites)) {
				$where['site'] = $site_id;
			}
		}
		return $this->getWhere($where);
	}
	
	/**
	 * where - 优惠价
	 *
	 */
	public function wherePrice($price, $where = null)
	{
		$where = $this->getWhere($where);
		list($pr, $min, $max, $count) = $price;
		if ($pr) {			
			if (2 > $count) {
				switch ($pr) {
					case '9.9':
						$where['price[<]'] = 10;
						break;
					case '20':
					case '50':
					case '100':
						$where['price[<>]'] = [$min, $max];
						break;
					default:
						$where['price[<=]'] = $pr;
				}
			} elseif (!$min) {
				$where['price[<=]'] = $max;
			} elseif (!$max) {
				$where['price[>=]'] = $min;
			} else {
				$where['price[<>]'] = [$min, $max];
			}
		}
		# print_r($where);
		return $this->getWhere($where);
	}
	
	/**
	 * query - 范围
	 */
	public function queryScope($price, $type = 'price')
	{
		$price = trim($price);
		$min = $max = '';
		$count = 0;
		if ($price) {
			$prices = preg_split('/\s*_\s*/', $price);
			$count = count($prices);
			# print_r([$price, $prices, $count]);
			if (2 > $count) {
				if ('price' == $type) {
					switch ($price) {
						case '9.9':
							$max = 10;
							break;
						case '20':
							$min = 10;
							$max = 20;
							break;
						case '50':
							$min = 20;
							$max = 50;
							break;
						case '100':
							$min = 50;
							$max = 100;
							break;
						default:
							$max = $price;
					}
				} elseif ('save' == $type) {
					switch ($price) {
						case '10':
							$min = 10;
							$max = 19.99;
							break;
						case '20':
							$min = 20;
							$max = 49.99;
							break;
						case '50':
							$min = 50;
							$max = 99.99;
							break;
						default:
							$min = $price;
					}
				} elseif ('sale' == $type) {
					switch ($price) {
						case '100':
							$min = 100;
							$max = 499;
							break;
						case '500':
							$min = 500;
							$max = 999;
							break;
						case '1000':
							$min = 1000;
							$max = 4999;
							break;
						default:
							$min = $price;
					}
				}
			} else {
				$min = isset($prices[0]) ? $prices[0] : '';
				$max = isset($prices[1]) ? $prices[1] : '';
				if (in_array($type, ['price', 'save', 'sale']) && is_numeric($min) && is_numeric($max)) {
					$max = $min > $max ? '' : $max;
				}
			}
		}
		return $arr = [$price, $min, $max, $count];
	}
	
	/**
	 * where - 省钱
	 *
	 */
	public function whereSave($save, $where = null)
	{
		$where = $this->getWhere($where);
		list($pr, $min, $max, $count) = $save;
		if ($pr) {
			if (2 > $count) {
				switch ($pr) {
					case '10':
					case '20':
					case '50':
						$where['save[<>]'] = [$min, $max];
						break;
					default:
						$where['save[>=]'] = $pr;
				}
			} elseif (!$min) {
				$where['save[<=]'] = $max;
			} elseif (!$max) {
				$where['save[>=]'] = $min;
			} else {
				$where['save[<>]'] = [$min, $max];
			}
		}
		return $this->getWhere($where);
	}
	
	/**
	 * where - 开始
	 *
	 */
	public function whereStart($start_time, $where = null)
	{
		$where = $this->getWhere($where);
		list($pr, $min, $max, $count) = $start_time;
		if ($pr) {
			$start_tm = $pr;
			switch ($start_tm) {
				case 'today':
					$start_tm = self::$attr['date'];
					break;
				case 'yesterday':
					$start_tm = date('Y-m-d', self::$attr['time'] - 86400);
					break;
				case 'DBY':
					$start_tm = self::$DBY;
					break;
				case 'last_week':
					$days = 6 + self::$week;
					$start_tm = date('Y-m-d', self::$attr['time'] - 86400 * $days) . '_' . self::$DBY;
					break;
			}
			$start_times = $this->datetime($start_tm);
			$start_count = count($start_times);
			if (1 < $start_count && $start_times[1]) {
				$start_times[0] = $start_times[0] ? : self::$now;
				$where['start[<>]'] = $start_times;
			} else {
				$where['start[>=]'] = $start_times[0];
			}
		}
		return $this->getWhere($where);
	}
	
	/**
	 * where - 结束
	 *
	 */
	public function whereEnd($end_time, $where = null)
	{
		$where = $this->getWhere($where);
		list($pr, $min, $max, $count) = $end_time;
		if ($pr) {
			$end_tm = $pr;
			switch ($end_tm) {
				case 'today':
					$end_tm = self::$attr['date'] . ' 23:59:59';
					break;
				case 'tomorrow':
					$tomorrow = date('Y-m-d', self::$attr['time'] + 86400);
					$end_tm = $tomorrow . '_' . $tomorrow . ' 23:59:59';
					break;
				case 'DAT':
					$end_tm = date('Y-m-d', self::$attr['time'] + 172800);
					break;
				case 'weekend':					
					$three_days_from_now = date('Y-m-d', self::$attr['time'] + 259200);
					$days = 7 - self::$week;
					$end_tm = $three_days_from_now . '_' . date('Y-m-d', self::$attr['time'] + 86400 * $days) . ' 23:59:59';
					break;
			}
			$end_times = $this->datetime($end_tm);
			$end_count = count($end_times);
			if (1 < $end_count && $end_times[1]) {
				$end_times[0] = $end_times[0] ? : self::$now;
				$where['end[<>]'] = $end_times;
			} else {
				$where['end[>=]'] = $end_times[0];
			}
		} else {
			$where['end[>]'] = self::$now;
		}
		return $this->getWhere($where);
	}
	
	/**
	 * where - 月销
	 *
	 */
	public function whereSale($sold, $where = null)
	{
		$where = $this->getWhere($where);
		list($pr, $min, $max, $count) = $sold;
		if ($pr) {
			if (2 > $count) {
				switch ($pr) {
					case '100':
					case '500':
					case '1000':
						$where['sold[<>]'] = [$min, $max];
						break;
					default:
						$where['sold[>=]'] = $pr;
				}
			
			} elseif (!$min) {
				$where['sold[<=]'] = $max;
			} elseif (!$max) {
				$where['sold[>=]'] = $min;
			} else {
				$where['sold[<>]'] = [$min, $max];
			}
		}
		return $this->getWhere($where);
	}
	
	/**
	 * where - 分类
	 *
	 */
	public function whereCategory($category_id, $Category = null, $where = null)
	{
		$where = $this->getWhere($where);
		$subclass_id = null;
		if ($category_id) {
			$where['category_id'] = $category_id;
			if (in_array($category_id, $Category->keys())) {
				$where['category_id'] = $Category->subIds($category_id);
				
			} else {
				$subclass_id = $category_id;
				$category_id = $Category->supId($category_id);
			}
		}
		$this->getWhere($where);
		return [$category_id, $subclass_id];
	}
	
	/**
	 * 排序
	 *
	 */
	public function orderBy($sort = '', $order = '')
	{
		$order_by = null;
		$sort_by = $sort ? : '';
		$sorts = [
			'' => 'updated',
			'price' => 'price',
			'save' => 'save',
			'start' => 'start',
			'end' => 'end',
			'sale' => 'sold',
			'update' => 'updated',
			'id' => 'list_id',
		];
		$orders = [
			'' => 'DESC',
			'price' => 'ASC',
			'save' => 'DESC',
			'start' => 'DESC',
			'end' => 'ASC',
			'sale' => 'DESC',
			'update' => 'DESC',
			'id' => 'DESC',
		];	
		if (isset($sorts[$sort_by])) {
			$order_input = strtoupper($order);
			if (!in_array($order_input, ['ASC', 'DESC'])) {
				$order_input = $orders[$sort_by];
			}
			$order_by = [$sorts[$sort_by] => $order_input];
		}
		return $order_by;
	}
}
