<?php
namespace app\model;

class AlimamaProductCategory extends \Astro\Database
{
	public $db_name = 'shopping';
	public $table_name = 'alimama_product_category';
	public $primary_key = 'category_id';
	public $return = [];
	
	public static $root_ids = null;
	public static $tree = null;
	public static $keys = null;

	public function subClasses($cid = -1)
	{
		$where = [
			'upper_id' => $cid,
			'total[>]' => 0,
		];
		$column = ['category_id','title'];
		$option = ['upper_id', 200];
		$all = $this->select($where, $column, $option);
		return $all;
	}
	
	/**
	 * 获取主类目
	 *
	 */
	public function rootIds()
	{
		if (null !== self::$root_ids) {
			return self::$root_ids;
		}
		
		/* 获取数据 */
		$where = "`upper_id` = -1";
		$where = [
			'OR' => [
				'upper_id' => '-1',
				'upper_id[>]' => 0,
			],
			'total[>]' => 0,
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
		return self::$root_ids = $arr;
	}
	
	/**
	 * 分类多维数组
	 *
	 */
	public function tree($data = null)
	{
		if (null !== self::$tree) {
			return self::$tree;
		}
		
		if (null === $data) {
			$data = $this->rootIds();
		}
		
		/* 分开 */
		$root = [];
		$leaf = [];
		$primaryKey = $this->primary_key;
		foreach ($data as $key => $value) {
			$datum = (object) $value;
			if (-1 < $datum->upper_id) {
				if (!isset($leaf[$datum->upper_id])) {
					$leaf[$datum->upper_id] = [];
				}
				$leaf[$datum->upper_id] [$datum->$primaryKey]= $datum;
			} else {
				$datum->leaves = [];
				$root[$datum->$primaryKey] = $datum;
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
		return self::$tree = $tree;
	}
	
	/**
	 * tree 的键名
	 *
	 */
	public function keys($tree = null)
	{
		if (null !== self::$keys) {
			return self::$keys;
		}
		
		if (null === $tree) {
			$tree = $this->tree();
		}
		
		return self::$keys = array_keys($tree);
	}
	
	/**
	 * 获取子类目
	 *
	 */
	public function subclass($category_id)
	{
		$subclass = [];
		if ($category_id) {
			$tree = $this->tree();
			if (isset($tree[$category_id])) {
				$subclass = $tree[$category_id]->leaves;
			}
		}
		return $subclass;
	}
	
	
	/**
	 * 获取子类 ID
	 *
	 */
	public function subIds($id, $data = null)
	{
		if (null === $data) {
			$data = $this->tree();
		}
		
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
	public function supId($id, $data = null)
	{
		if (null === $data) {
			$data = $this->rootIds();
		}
		
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
