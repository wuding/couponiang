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
	public function lists($limit = 10, $Category = null)
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
		$filters = [$price, $save, $start_time, $end_time, $sold, $site_id, $category_id];
		$filter = 0;
		foreach ($filters as $f) {
			if (!empty($f)) {
				$filter++;
			}
		}
		$overflow = 0;
		$where = $item_id = $api_result = null;

		/* 搜链接 */
		/* 清除已经定义的变量
		foreach (get_defined_vars() as $key => $value) {
			if (!in_array($key, ['query'])) {
				eval("unset(\$$key);");
			}
		}
		unset($key, $value);
		*/

		$pattern = '/^http(|s):\/+(item|detail|h5)(|\.m)\.(taobao|tmall)\.com(\/|[a-z\/]+)(item|detail)\.htm(?<query_string>.*)/i';
		if (preg_match($pattern, $query, $matches)) {
			$query_str = trim(array_pop($matches), '?');
			parse_str($query_str, $query_arr);
			$item_id = isset($query_arr['id']) ? $query_arr['id'] : '';			
			$where = ['item_id' => $item_id];
			# print_r(get_defined_vars());exit;
		}
		
		/* 商品 */
		$prices = $List->queryScope($price);
		$saves = $List->queryScope($save, 'save');
		$sales = $List->queryScope($sold, 'sale');
		$starts = $List->queryScope($start_time, 'start');
		$ends = $List->queryScope($end_time, 'end');
		$cats = $List->whereCategory($category_id, $Category); // 分类	
		if (!$where) {				
			$List->whereQuery($query); // 关键词		
			$List->whereSite($site_id); // 网站		
			$List->wherePrice($prices); // 优惠价		
			$List->whereSave($saves); // 省钱		
			$List->whereStart($starts); // 开始		
			$List->whereEnd($ends); // 结束
			$where = $List->whereSale($sales); // 月销
		}
		# print_r($where); exit;
		
		// 计算
		$count = $List->count($List->table_name, $where);
		if (!$count && $item_id) {
			$filename = $GLOBALS['PHP']->config['var']['robot_host'] . "/robot/alimama/search/promo?q=$item_id&debug&api=1";
			$str = file_get_contents($filename);
			$json = json_decode($str);
			if ('NULL' != gettype($json)) {
				list($tao_token, $item_list) = $json;
				# print_r($json);exit;
				if ($tao_token && $item_list) {
					$count = $List->count($List->table_name, $where);
				}
			} else {
				$api_result = '';
			}
		}
		$pages = ceil($count / $limit);
				
		// 结果集
		if ($count) {
			$order_by = $List->orderBy($sort, $order); // 排序
			$overflow = ($page >= $pages);
			$page = $overflow ? $pages : $page;
			$offset = $page * $limit - $limit;
			$items = $List->items($where, $order_by, $limit, $offset);
		}		
		return get_defined_vars();
	}
}
