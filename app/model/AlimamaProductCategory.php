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
		/* 获取数据 */
		$where = "`upper_id` = -1";
		$where = [
			'OR' => [
				'upper_id' => '-1',
				'upper_id[>]' => 0,
			],
		];
		$column = '*';
		$column = ['category_id','upper_id','title'];
		$option = ['category_id', 200];
		$all = $this->select($where, $column, $option);
		
		/* 转类型 */
		$arr = [];
		if ($all) {
			foreach ($all as $key => $value) {
				if (is_array($value)) {
					$k = $value['category_id'];
					$arr [$k]= $value;
				} elseif (is_object($value)) {
					$arr = (array) $all;
					break;
				}
			}
		}
		return $arr;
	}
	
	/**
	 * 分类多维数组
	 *
	 */
	public function tree($data)
	{
		/* 分开 */
		$root = [];
		$leaf = [];
		foreach ($data as $key => $value) {
			$datum = (object) $value;
			if (-1 < $datum->upper_id) {
				if (!isset($leaf[$datum->upper_id])) {
					$leaf[$datum->upper_id] = [];
				}
				$leaf[$datum->upper_id] [$datum->category_id]= $datum;
			} else {
				$datum->leaves = [];
				$root[$datum->category_id] = $datum;
			}
		}
		
		/* 合并 */
		$tree = [];
		foreach ($root as $key => $value) {
			if (isset($leaf[$key])) {
				$value->leaves = $leaf[$key];
			}
			$tree [$key]= $value;
		}
		return $tree;
	}
	
	/**
	 * 获取子类 ID
	 *
	 */
	public function subIds($id, $data)
	{
		$keys = [];
		if (isset($data[$id])) {
			$root = $data[$id];
			$keys = array_keys($root->leaves);
		}
		return $keys;
	}
	
	/**
	 * 获取上级 ID
	 *
	 */
	public function supId($id, $data)
	{
		$no = null;
		if (isset($data[$id])) {
			$datum = (object) $data[$id];
			if (0 < $datum->upper_id) {
				$no = $datum->upper_id;
			}
		}
		return $no;
	}
}
