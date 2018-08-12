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
	public $primary_key = 'id';
	
	public function __construct($arg = [])
	{
		global $PHP;
		if (!$arg) {
			$arg = $PHP->config['database'];
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
		$this->setVar($arg);
		$arg = get_object_vars($this);
		unset($arg['inst'], $arg['adapter']);
		$this->getAdapter($this->adapter, $arg);
	}
	
	public function _init()
	{
		
	}
	
	public function getAdapter($name = null, $arg = null)
	{
		if (null !== $this->inst) {
			return $this->inst;
		}
		
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
