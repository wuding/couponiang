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
	public function items($condition = [])
	{
		$now = date('Y-m-d H:i:s');
		
		/* 获取数据 */
		$where = "`upper_id` = -1";
		$where = [
			'end[<]' => $now,
		];
		$where += $condition;
		$column = '*';
		$column = ['excel_id','category_id','name', 'pic'];
		$option = ['excel_id', 40];
		$join = [
			'[>]alimama_product_category' => ['class' => 'title'],
		];
		$all = $this->select($where, $column, $option, null, $join);
		return $all;
	}
}
