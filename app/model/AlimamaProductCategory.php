<?php
namespace app\model;

class AlimamaProductCategory extends \Astro\Database
{
	public $db_name = 'com_urlnk';
	public $table_name = 'alimama_product_category';
	public $primary_key = 'category_id';
	
	/**
	 * 获取主类目
	 *
	 */
	public function rootIds()
	{
		$where = "`upper_id` = -1";
		$where = ['upper_id' => '-1'];
		$column = '*';
		$option = ['category_id', 20];
		$all = $this->select($where, $column, $option);
		$arr = [];
		foreach ($all as $key => $value) {
			if (is_array($value)) {
				$arr []= (object) $value;
			} elseif (is_object($value)) {
				$arr = $all;
				break;
			}
		}
		return $arr;
	}
}
