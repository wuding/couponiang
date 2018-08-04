<?php
namespace Astro\Database;

class Medoo
{
	public static $instance;
	public $driver = 'mysql';
	public $host = 'localhost';
	public $port = 3306;
	public $user = 'root';
	public $password = 'root';
	public $db_name = 'mysql';
	public $table_name = 'user2';
	public $primary_key = null;
	
	public function __construct($arg = [])
	{
		if ($arg) {
			$this->init($arg);
		}
	}
	
	public function init($arg = [])
	{
		$this->setVar($arg);
		# print_r([$arg, __METHOD__, __LINE__, __FILE__]); exit;
		$this->getInstance();
	}
	
	public function setVar($arg = [])
	{
		# print_r($arg);
		foreach ($arg as $key => $value) {
			$this->$key = $value;
		}
	}
	
	public function getInstance()
	{
		$options = [
			'database_type' => $this->driver,
			'database_name' => $this->db_name,
			'server' => $this->host,
			'username' => $this->user,
			'password' => $this->password,
			'port' => $this->port,
		];
		# print_r($options); exit; 
		return self::$instance = new \Medoo\Medoo($options);
	}
	
	/**
	 * 获取多条数据
	 *
	 */
	public function select($where = [], $columns = null, $option = [], $group = [], $join = [])
	{
		$order = null;
		$limit = null;
		$table = $this->table_name;
		
		/* 排序和条目 */
		if (isset($option[0])) {
			$order = $option[0];
			if (isset($option[1])) {
				$limit = $option[1];
			}
		}
		
		/* 分组 */
		if (isset($group[0])) {
			$this->group_by = $group[0];
			if (isset($group[1])) {
				$this->having = $group[1];
			}
		}
		
		
		$where['ORDER'] = $order;
		$where['LIMIT'] = $limit;
		
		# self::$instance->debug(); 
		if (!$join) {
			# print_r([$join, __METHOD__, __LINE__, __FILE__]);
			$all = self::$instance->select($table, $columns, $where);
		} else {
			# print_r([$join, __METHOD__, __LINE__, __FILE__]);
			$all = self::$instance->select($table, $join, $columns, $where);
		}
		# print_r([self::$instance->sql]);
		# print_r([$all, self::$instance, $table, $columns, $where, $join]);exit; 
		return $all;
	}
}
