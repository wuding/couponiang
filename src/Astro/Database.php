<?php
namespace Astro;

class Database
{
	public static $instance;
	public $inst;
	public $adapter = 'Medoo';
	public $driver = 'mysql';
	public $host = 'localhost';
	public $port = 3306;
	public $user = 'root';
	public $password = 'root';
	public $db_name = 'mysql';
	public $table_name = 'user';
	public $primary_key = null;
	
	public function __construct($arg = [])
	{
		global $PHP;
		if (!$arg) {
			$arg = $PHP->config['database'];
			# print_r([$arg, __METHOD__, __LINE__, __FILE__]); exit;
		}
		$this->init($arg);
		$this->_init();
	}
	
	public function setVar($arg = [])
	{
		
		foreach ($arg as $key => $value) {
			$this->$key = $value;
		}
	}
	
	public function init($arg = [])
	{
		# print_r([$arg, __METHOD__, __LINE__, __FILE__]);
		$this->setVar($arg);
		$arg = [
			'host' => $this->host,
			'port' => $this->port,
			'db_name' => $this->db_name,
			'table_name' => $this->table_name,
			'username' => $this->user,
			'password' => $this->password,
			'driver' => $this->driver,
		];
		# print_r([$arg, __METHOD__, __LINE__, __FILE__]);
		$this->getAdapter($this->adapter, $arg);
	}
	
	public function _init()
	{
		
	}
	
	public function getAdapter($name, $arg)
	{
		$name = str_replace('_', ' ', $name);
		$name = ucwords($name);
		$name = str_replace(' ', '', $name);
		$class = '\Astro\Database\\' . $name;
		return $this->inst = new $class($arg);
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
}
