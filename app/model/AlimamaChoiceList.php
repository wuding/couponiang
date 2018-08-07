<?php
namespace app\model;

class AlimamaChoiceList extends \Astro\Database
{
	public $db_name = 'com_urlnk87';
	public $table_name = 'alimama_choice_list';
	public $primary_key = 'list_id';
	
	/**
	 * 获取产品列表
	 *
	 */
	public function items($condition = [], $sort = null, $limit = 40)
	{
		$sort = $sort ? : [$this->primary_key => 'DESC'];
		$now = date('Y-m-d H:i:s');
		
		/* 获取数据 */
		$where = [];
		$where += $condition;
		
		$column = '*';
		$column = [$this->primary_key, 'category_id', 'title', 'pic', 'price'];
		$option = [$sort, $limit];
		
		$join = [];
		$all = $this->select($where, $column, $option, null, $join);
		return $all;
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
}
