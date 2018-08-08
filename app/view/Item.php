<?php
namespace app\view;

use Pagerfanta\Adapter\SimpleAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\DefaultView;

class Item
{
	public function __construct()
	{
	}
	
	/**
	 * 超大图
	 *
	 *
	 */
	public static function huge($data, $view = '')
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
			if (1 == $no) {
				$style = ' style="clear: left;"';
			}
			
			$li = <<<HEREDOC
			<li $style>
				<div>
					<a href="/item/{$row['list_id']}">
						<p><img src="{$row['pic']}_400x400.jpg"></p>
						<var>￥{$row['price']}</var>
						<b>{$row['title']}</b>
					</a>
				</div>
			</li>
HEREDOC;

			$lis .= $li;
			$i++;
		}
		return $lis;
	}
	
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
}
