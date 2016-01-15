<?php

namespace Ilum\Drivers;

use PDO;

class ConnectPDO
{
	protected $pdo;
	
	protected $count = 0;
	
	protected $error = false;
	
	protected $result = null;
	
	protected $prepare;
	
	public function __construct($dns, $user, $pass, $option = array())
	{
		try {
			$this->pdo = new PDO($dns, $user, $pass, $option);
		}catch(PDOException $e){
			die($e->geMessage());
		}
	}
	
	protected function prepareQuery($sql, $values)
	{
		if($this->prepare = $this->pdo->prepare($sql)) {
			$x = 1;
			for($i = 0; $i < count($values); $i++) {
				$this->prepare->bindValue($x, $values[$i]);
				$x++;
			}
			
			try {
				if($this->prepare->execute()) {
					$this->count = $this->prepare->rowCount();
				}
			}catch(PDOException $e){
				die($e->getMessage());
			}
			
		}else {
			$this->error = true;
		}
		
		return $this;
	}
	
	protected function action($sql, $values)
	{
		if(!$this->prepareQuery($sql, $values)->error()){
			return true;
		}
	}

	public function schemaQuery($name)
	{
		if(!$this->pdo->query($name)){
            echo 'hubo un error';
        }
	}
	
	public function select($sql, $values)
	{
		$this->action("SELECT ". $sql, $values);
	}
	
	public function delete($sql, $values)
	{
		$this->action('DELETE '.$sql, $values);
	}
	
	public function update($table, $id, $fields)
	{
		$action = '';
		$x = 1;
		foreach($fields as $key => $col){
			$action .= "{$key} = ?";
			if($x < count($fields)){
				$action .= ', ';
			}
			$x++;
		}
		$sql = "UPDATE {$table} SET {$action} WHERE id = {$id}";
		
		$this->action($sql, array_values($fields));
	}
	
	public function insert($sql, $values)
	{
		$this->action($sql, $values);
	}
	
	protected function rowCount()
	{
		return $this->count;
	}
	
	public function count()
	{
		return $this->rowCount();
	}
	
	public function result()
	{
		return $this->result = $this->prepare->fetchAll(PDO::FETCH_OBJ);
	}
	
	public function error()
	{
		return $this->error;
	}
}