<?php
namespace Astro\Database;

class Medoo extends \Astro\Core
{
	public $inst;
	public $driver = 'mysql';
	public $host = 'localhost';
	public $port = 3306;
	public $user = 'root';
	public $password = 'root';
	public $db_name = 'mysql';
	public $table_name = 'user2';
	public $primary_key = null;
	public $return = null;
	public $logs = [];
	
	public function __construct($arg = [])
	{
		if ($arg) {
			$this->initialization($arg);
		}
	}
	
	public function initialization($arg = [])
	{
		$this->_set($arg);
		# print_r([$arg, __METHOD__, __LINE__, __FILE__]); exit;
		$this->getInstance();
	}
	
	public function getInstance()
	{
		if (null !== $this->inst) {
			return $this->inst;
		}
		
		$options = [
			'database_type' => $this->driver,
			'database_name' => $this->db_name,
			'server' => $this->host,
			'username' => $this->user,
			'password' => $this->password,
			'port' => $this->port,
		];
		# print_r($options); exit; 
		return $this->inst = new \Medoo\Medoo($options);
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
		# $table = $this->db_name . '.' . $this->table_name;
		
		/* 排序和条目 */
		if (isset($option[0])) {
			$order = $option[0];
			if (isset($option[1])) {
				$limit = $option[1];
				if (isset($option[2])) {
					$offset = $option[2];
					$limit = [$offset, $limit];
				}
			}
		}
		
		/* 分组 */
		if (isset($group[0])) {
			$this->group_by = $group[0];
			$where['GROUP'] = $group[0];
			if (isset($group[1])) {
				$this->having = $group[1];
			}
		}
		
		
		$where['ORDER'] = $order;
		$where['LIMIT'] = $limit;
		
		# $this->inst->debug(); 
		if (!$join) {
			# print_r([$join, __METHOD__, __LINE__, __FILE__]);
			$all = $this->inst->select($table, $columns, $where);
		} else {
			# print_r([$join, __METHOD__, __LINE__, __FILE__]);
			$all = $this->inst->select($table, $join, $columns, $where);
		}
		
		# print_r([$all, self::$_instance, $table, $columns, $where, $join]);exit; 
		return $this->logs($this->inst->sql, 'select') ? : $all;
	}

	public function logs($sql, $type = null)
	{
		if (is_array($this->return)) {
			if (in_array($type, $this->return)) {
				$this->logs[] = $sql;
			}
		} elseif (is_string($this->return) && $type === $this->return) {
			return $sql;
		}
		return false;
	}

	public function getProp($name)
	{
		return $this->$name;
	}
	
	public function sel($where = [], $columns = null, $option = [], $group = [], $join = [])
	{
		$opt = [[$this->primary_key => 'DESC'], 1, 0];
		$option = $option + $opt;
		
		$row = null;
		$all = $this->select($where, $columns, $option, $group, $join);
		if (isset($all[0])) {
			$row = (object) $all[0];
		}
		return $row;
	}
	
	public function find($id, $columns = '*', $option = [], $group = [], $join = [])
	{
		$opt = [[$this->primary_key => 'DESC'], 1, 0];
		$option = $option + $opt;
		
		$where = [$this->primary_key => $id];
		$row = $this->sel($where, $columns, $option, $group, $join);
		return $row;
	}

	public function update($data, $where = null, $call = null)
	{
		$where = $this->sqlWhere($where);
		$table = $this->table_name;
		$exec = $this->inst->update($table, $data, $where);
		$exec = $this->logs($this->inst->sql, $call ? : 'update') ? : $exec;
		return $exec;
	}

	public function insert($data)
	{
		$table = $this->table_name;
		$exec = $this->inst->insert($table, $data);
		return $this->logs($this->inst->sql, 'insert') ? : $exec;
	}

	public function sqlWhere($where, $type = 'AND')
	{
		if (!is_array($where)) {
			$where = is_numeric($where) ? [$this->primary_key => $where] : $where;
			return $where;
		}
		return $where;
	}
	
	public function __call($name, $arguments)
	{
		$func = $this->inst;
		$arg = $arguments;
		# print_r($arg);exit;
		
		$str = [];
		foreach ($arg as $key => $value) {
			$str []= "\$arg[$key]";
		}
		$str = implode(', ', $str);
		$code = "\$result = \$func->\$name($str);";
		eval($code);
		return $result;
	}

	public function __get($name)
	{
		$this->inst->$name;
	}
}
