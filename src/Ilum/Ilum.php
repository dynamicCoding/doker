<?php

namespace Ilum;

use Ilum\Contracts\IlumInterface;
use Ilum\Connector;
use Disting\Config;

class Ilum implements IlumInterface
{
	protected $cnn;
	
	protected $table;
	
	protected $column;
	
	protected $equal = array();
	
	protected $or = array();
	
	public function __construct()
	{
		$config = (new Config)->load('Database');
		if($config->data('run_database')) {
			$this->cnn = $this->connectConfig($config);
		}
	}
	
	public function select($table, $column = array())
	{
		$this->table = $table;
		$this->column = $column;
		
		return $this;
	}
	
	public function insert($table, $fields = array())
	{
		$this->cnn->insert($table, $fields);
	}
	
	public function update($table, $id, $fields = array())
	{
		$this->cnn->update($table, $id, $fields);
	}
	
	public function delete($table, $fields)
	{
		$this->cnn->delete($table, $fields);
	}
	
	public function ors($key, $val)
	{
		if($this->search($key)) {
			$this->or[$key] = $val;
		}
		
		return $this;
	}
	
	public function equal($key, $val)
	{
		if($this->search($key)) {
			$this->equal[$key] = $val;
		}
		
		return $this;
	}
	
	protected function search($s)
	{
		if(!strstr($s, '=')) {
			trigger_error("no se encontro el signo equal", E_USER_ERROR);
			exit;
		}
		return true;
	}
	
	public function execute($orderBy = '')
	{
		$this->cnn->operation(
			$this->table, $this->column, $this->equal, $this->or, $orderBy
		);
		
		if(!empty($this->equal)){
			unset($this->equal);
		}
		
		if(!empty($this->or)) {
			unset($this->or);
		}
		
		return $this;
	}
	
	public function save()
	{
		
	}
	
	public function result()
	{
		return $this->cnn->result();
	}
	
	public function count()
	{
		return $this->cnn->count();
	}

	public function migration($name)
	{
		$this->cnn->createDb($name);
	}
	
	protected function connectConfig($c)
	{
		$d = $c->data('default');
		$host = $c->data('drivers.'.$d.'.host');
		$user = $c->data('drivers.'.$d.'.username');
		$pass = $c->data('drivers.'.$d.'.password');
		$db = $c->data('drivers.'.$d.'.database');
		$charset = $c->data('drivers.'.$d.'.charset');
		$collation = $c->data('drivers.'.$d.'.collation');
		
		$cnn = new Connector($d, $host, $user, $pass, $db, $charset, $collation);
		
		return $cnn;
	}
}