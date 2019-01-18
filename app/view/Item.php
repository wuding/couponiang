<?php
namespace app\view;

use app\view\Form;
use Pagerfanta\Adapter\SimpleAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\DefaultView;

class Item
{
	/*
	 ------------------------------------------------------------------
	 | 查看方式
	 ------------------------------------------------------------------
	 */
	
	/**
	 * desktop - 超大图
	 *
	 *
	 */
	public static function huge($data, $view = '', $query = null)
	{
		$cols = [
			'' => 5,
			'huge' => 4,
			'large' => 5,
			'medium' => 10,
			'small' => 5,
		];
		
		$col = isset($cols[$view]) ? $cols[$view] : $cols[''];
		
		
		$lis = '';
		$i = 1;
		foreach ($data as $row) {
			$obj = (object) $row;
			
			$no = $i % $col;
			$style = '';
			$class = '';
			$save = '券';
			$sold = "已领 $obj->sold 张券";
			$title = $tip = $obj->title;
			if (1 == $no) {
				$style = ' style="clear: left;"';
			}			
			if (3 == $obj->site) {
				$save = '省';
				$sold = "$obj->sold 件已售";
				$class = ' class="tuan"';
			}
			
			// 关键词高亮
			if ($query && !preg_match('/^http(|s):\/+/i', $query)) {
				$queries = preg_split('/\s+/', $query);
				$arr = [];
				foreach ($queries as $q) {
					$q = trim($q);
					if ($q && !preg_match('/^-/', $q)) {
						if (!in_array($q, $arr)) {
							$arr[] = $q;
						}
					}
				}
				foreach ($arr as $q) {
					$title = preg_replace("/($q)/i", "<mark>$1</mark>", $title);
				}
			}
			
			$pic = preg_replace('/^http:/i', '', $obj->pic);

			$url = "/item/$obj->excel_id";
			if ($obj->tao_token) {
				$url = $GLOBALS['PHP']->config['var']['url_shortening'] . '/$' . $obj->tao_token . '$';
			}
			
			$li = <<<HEREDOC
			<li $style $class>
				<div title="$tip">
					<a href="$url" target="_blank" data-end="$obj->end" data-no="$i">
						<menu>{$save}￥$obj->save</menu>
						<p><img src="{$pic}_200x200.jpg"></p>
						<time>$obj->end</time>
						<span>
							<var>￥$obj->price</var>
							<s>$sold</s>
						</span>
						<b>$title</b>
					</a>
				</div>
			</li>
HEREDOC;

			$lis .= $li;
			$i++;
		}
		return $lis;
	}

	/**
	 * mobile - 瀑布流
	 */
	public static function dl($data, $view = '', $query = null)
	{
		$lis = ['', ''];
		$i = 0;
		foreach ($data as $row) {
			$odd = $i % 2;
			$obj = (object) $row;			
			$em = (3 == $obj->site) ? '<em>聚</em>' : '';
			$sold = "月销{$obj->sold}笔";

			$title = $obj->title;
			// 关键词高亮
			if ($query && !preg_match('/^http(|s):\/+/i', $query)) {
				$queries = preg_split('/\s+/', $query);
				$arr = [];
				foreach ($queries as $q) {
					$q = trim($q);
					if ($q && !preg_match('/^-/', $q)) {
						if (!in_array($q, $arr)) {
							$arr[] = $q;
						}
					}
				}
				foreach ($arr as $q) {
					$title = preg_replace("/($q)/i", "<mark>$1</mark>", $title);
				}
			}
			$pic = preg_replace('/^http:/i', '', $obj->pic);
			
			// 优惠券额
			$class = '';
			$save = '券';
			if (3 == $obj->site) {
				$save = '省';
				$class = ' class="tuan"';
			}

			$url = "/item/$obj->excel_id";
			if ($obj->tao_token) {
				$url = $GLOBALS['PHP']->config['var']['url_shortening'] . '/$' . $obj->tao_token . '$';
			}

			$li = <<<HEREDOC
			<dl $class>
				<a href="$url" target="_blank">
					<menu>{$save}￥$obj->save</menu>
					<img src="{$pic}_200x200.jpg">
					<span>
						$em
						<h4>$title</h4>
					</span>
					<data>
						<p>￥$obj->price</p>
						<s>$sold</s>
					</data>
				</a>
			</dl>
HEREDOC;

			$lis[$odd] .= $li;
			$i++;
		}
		return $lis;
	}
	
	


	/*
	 ------------------------------------------------------------------
	 | 通用功能
	 ------------------------------------------------------------------
	 */
	
	/**
	 * 地址生成器
	 *
	 */
	public static function url($uri = '', $page = null, $pages = null)
	{
		$queryString = $_SERVER['QUERY_STRING'];
		if (null !== $page) {
			// 异常
			if (null !== $pages) {
				$page = ($page > $pages) ? $pages : $page;
			}
			
			parse_str($queryString, $formData);
			// 精简
			foreach ($formData as $key => $value) {
				if (!$value && !is_numeric($value)) {
					unset($formData[$key]);
				}
			}
			$formData['page'] = $page;
			$queryString = http_build_query($formData);
		}
		$uri = $queryString ? $uri . '?' . $queryString : $uri;
		return $uri;
	}
	
	/**
	 * 分页器
	 *
	 */
	public static function paginator($count, $page = 1, $limit = 10, $data = [])
	{
		$adapter = new SimpleAdapter($count, $data);
		$pagerfanta = new Pagerfanta($adapter);

		$pagerfanta->setMaxPerPage($limit);
		$pagerfanta->setCurrentPage($page);
		
		$routeGenerator = function($page) {
			return \app\view\Item::url('', $page);
		};
		
		$view = new DefaultView();
		$options = [
			'proximity' => 3,
			'prev_message' => '上一页',
			'next_message' => '下一页',
		];
		return $pagination = $view->render($pagerfanta, $routeGenerator, $options);
	}
	
	#! 未使用
	public static function pagination($page, $pages)
	{
		$queryString = $_SERVER['QUERY_STRING'];
		parse_str($queryString, $formData);
		
		$last = $page + 1;
		$last = ($last > $pages) ? $pages : $last;
		
		$formData['page'] = $last;
		$queryStr = http_build_query($formData);
		
		$html = <<<HEREDOC
		
		$page/$pages
		<a href="?$queryStr">下一页</a>
HEREDOC;

		return $html;
	}


	/*
	 ------------------------------------------------------------------
	 | HTML select 标签元素
	 ------------------------------------------------------------------
	 */

	/**
	 * select - 排序方式
	 * 
	 */
	public static function selectOrder($order)
	{
		$orders = [
			'' => '方式',
			'asc' => '升序',
			'desc' => '降序',
		];
		return Form::select($orders, $order, ['name' => 'order']);
	}

	/**
	 * select - 排序字段
	 * 
	 */
	public static function selectSort($sort)
	{
		$sorts = [
			'' => '默认',
			'price' => '价格',
			'save' => '省钱',
			'start' => '开始',
			'end' => '结束',
			'sale' => '月销',
			# 'update' => '更新',
		];
		return Form::select($sorts, $sort, ['name' => 'sort']);
	}

	/**
	 * select - 价格
	 * 
	 */
	public static function selectPrice($price)
	{
		$prices = [
			'' => '价格',
			'9.9' => '9块9',
			'20' => '20元',
			'50' => '50元',
			'100' => '100',
		];
		return Form::select($prices, $price, ['name' => 'price']);
	}

	/**
	 * select - 省钱
	 * 
	 */
	public static function selectSave($save)
	{
		$saves = [
			'' => '省钱',
			'10' => '10元',
			'20' => '20元',
			'50' => '50元',
			'100' => '100',
		];
		return Form::select($saves, $save, ['name' => 'save']);
	}

	/**
	 * select - 开始时间
	 * 
	 */
	public static function selectStart($start_time)
	{
		$starts = [
			'' => '开始',
			'today' => '今天',
			'yesterday' => '昨天',
			'DBY' => '前天',
			'last_week' => '上周',
		];
		return Form::select($starts, $start_time, ['name' => 'start']);
	}

	/**
	 * select - 结束时间
	 * 
	 */
	public static function selectEnd($end_time)
	{				
		$ends = [
			'' => '结束',
			'today' => '今天',
			'tomorrow' => '明天',
			'DAT' => '后天',
			'weekend' => '周末',
		];
		return Form::select($ends, $end_time, ['name' => 'end']);
	}

	/**
	 * select - 月销量
	 * 
	 */
	public static function selectSale($sold)
	{				
		$sales = [
			'' => '月销',
			'100' => '100',
			'500' => '500',
			'1000' => '1千',
			'5000' => '5千',
		];
		return Form::select($sales, $sold, ['name' => 'sale']);
	}

	/**
	 * select - 站点
	 * 
	 */
	public static function selectSite($site_id, $arr = [])
	{
		$sites = [
			'' => '网站',
			1 => '淘宝',
			2 => '天猫',
			3 => '聚划算',
		];
		$sites = $arr + $sites;
		return Form::select($sites, $site_id, ['name' => 'site']);
	}

	/**
	 * select - 分类
	 * 
	 */
	public static function selectCategory($cat, $cat_id = null, $property = [], $arr = null)
	{
		if (null === $arr) {
			$arr = [
				'' => '全部',
			];
		}
		
		foreach ($cat as $c) {
			$arr[$c->category_id] = $c->title;
		}
		return Form::select($arr, $cat_id, $property);
	}
	
	/*
	 ------------------------------------------------------------------
	 | list - 分类及排序
	 ------------------------------------------------------------------
	 */

	/**
	 * 分类 - 横向导航条
	 */
	public static function catNav($cat, $cat_id = null, $query = null, $sort = null, $order = null)
	{
		/*
		$q = urlencode($query);
		$qu = $query ? "q=$q" : '';
		*/
		$lis = '';
		$sel = 0;
		$width = 90;
		$formData = [];
		if ($query) {
			$formData['q'] = $query;
		}
		if ($sort) {
			$formData['sort'] = $sort;
		}
		if ($order) {
			$formData['order'] = $order;
		}
		$formData = self::array_index($formData);
		$queryStr = http_build_query($formData);
		$allStr = $queryStr ? "?$queryStr" : '/';
		foreach ($cat as $c) {
			$class = '';
			if ($cat_id == $c->category_id) {
				 $class = 'class="cat"';
				 $sel++;
			}
			/*
			$url = "?category=$c->category_id";
			$url = $query ? $url . "&$qu" : $url;
			*/
			$formData['category'] = $c->category_id;
			$formData = self::array_index($formData);
			$queryStr = http_build_query($formData);
			
			$lis .= "<li $class><a id=\"cat_$c->category_id\" href=\"?$queryStr#cat_$c->category_id\">$c->title</a></li>";
			$width += 90;
		}

		$cls = (!$sel) ? 'class="cat"' : '';
		$all = "<li $cls><a id=\"cat_\" href=\"$allStr\">全部</a></li>";

		$lis = '<ol style="width: ' . $width . 'px">' . $all . $lis . '</ol>';
		return $lis;
	}

	/**
	 * 排序 - 挑选列表
	 */
	public static function orderList($category_id = null)
	{
		$arr = [
			'' => '默认',
			'price:asc' => '价格升序',
			'price:desc' => '价格降序',
			'start:desc' => '开始时间',
			'end:asc' => '结束时间',
		];

		$queryString = $_SERVER['QUERY_STRING'];
		parse_str($queryString, $formData);

		$sort = isset($formData['sort']) ? $formData['sort'] : '';
		$order = isset($formData['order']) ? $formData['order'] : '';
		$cur = $sort && $order ? $sort . ':' . $order : '';
		
		# $category_id = isset($formData['category']) ? $formData['category'] : '';
		$fragment = $category_id ? "#cat_$category_id" : '';
		
		$lis = '';
		$name = '默认';
		foreach ($arr as $key => $value) {
			$keys = explode(':', $key);
			$len = count($keys);
			$sel = $keys[0];
			unset($formData['order'], $formData['sort']);
			if ($sel) {
				$formData['sort'] = $sel;
			}
			if (1 < $len) {
				$formData['order'] = $keys[1];
				$sel .= ':' . $formData['order'];
			}
			$formData = self::array_index($formData);
			$queryStr = http_build_query($formData);

			$class = '';
			$sup = '';
			if ($cur == $sel) {
				$class = 'class="sel"';
				$sup = '<sup>√</sup>';
				$name = $value;
			}			
			$lis .= "<li $class>$sup<a href=\"?$queryStr$fragment\">$value</a></li>";
		}
		return [$name, $lis];
	}

	/**
	 * 排序 - 选项卡
	 */
	public static function orderTab($name = null, $sort = '', $category_id = null)
	{
		$arr = [
			'sale:desc' => '销量',
			'save:desc' => '省钱',
		];

		$queryString = $_SERVER['QUERY_STRING'];
		parse_str($queryString, $formData);

		$sort = isset($formData['sort']) ? $formData['sort'] : $sort;
		$order = isset($formData['order']) ? $formData['order'] : '';
		$cur = $sort && $order ? $sort . ':' . $order : '';
		
		$fragment = $category_id ? "#cat_$category_id" : '';

		$lis = '';
		$sup = 0;
		foreach ($arr as $key => $value) {
			$keys = explode(':', $key);
			$len = count($keys);
			$sel = $formData['sort'] = $keys[0];
			unset($formData['order']);
			if (1 < $len) {
				$formData['order'] = $keys[1];
				$sel .= ':' . $formData['order'];
			}
			$formData = self::array_index($formData);
			$queryStr = http_build_query($formData);

			$class = '';
			if ($cur == $sel) {
				$class = 'class="cur"';
				$sup++;
			}
			$lis .= "<li><a $class href=\"?$queryStr$fragment\">$value</a></li>";
		}
		
		$cls = '';
		if (!$sup) {
			$cls = 'class="cur"';
		}
		$list = "<li><a $cls id=\"order_first\" href=\"javascript:order()\">$name</a></li>";
		$lis = $list . $lis;
		return $lis;
	}
	
	/*
	 ------------------------------------------------------------------
	 | functions - 核心功能
	 ------------------------------------------------------------------
	 */
	
	/**
	 * array - 根据设置好的键名索引排序
	 *
	 */
	public static function array_index($data = [])
	{
		$index = ['q', 'category', 'site', 'price', 'save', 'start', 'end', 'sale', 'sort', 'order', 'page', 'debug'];
		$filp = array_flip($index);
		$arr = [];
		foreach ($data as $key => $value) {
			$idx = isset($filp[$key]) ? $filp[$key] : null;
			
			$arr[$idx] = [$key, $value];
		}
		ksort($arr);
		
		$form = [];
		foreach ($arr as $row) {
			
			if ($row[1]) {
				$k = $row[0];
				$form[$k] = $row[1];
			}
		}
		return $form;
	}
}
