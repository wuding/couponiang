<?php
namespace app\model;

class AlimamaChoiceExcel extends \Astro\Database
{
	public $db_name = 'com_urlnk';
	public $table_name = 'alimama_choice_excel';
	public $primary_key = 'excel_id';
	
	/**
	 * 获取产品列表
	 *
	 */
	public function items($condition = [], $sort = null, $limit = 40)
	{
		$sort = $sort ? : ['excel_id' => 'DESC'];
		$now = date('Y-m-d H:i:s');
		
		/* 获取数据 */
		$where = "`upper_id` = -1";
		$where = [];
		$where += $condition;
		
		$column = '*';
		$column = ['excel_id','category_id','name', 'pic'];
		$option = [$sort, $limit];
		
		$join = [
			'[>]alimama_product_category' => ['class' => 'title'],
		];
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
}
