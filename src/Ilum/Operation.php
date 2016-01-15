<?php

namespace Ilum;


abstract class Operation {
	
	public function insert($table, $fields = array())
	{
		$action = '';
		$x = 1;
		foreach($fields as $col){
			$action .= '?';
			if($x < count($fields)){
				$action .= ', ';
			}
			$x++;
		}
		$sql = "INSERT INTO {$table}(`".implode('`,`',array_keys($fields))."`) VALUE({$action})";
		
		$this->connect->insert($sql, array_values($fields));
	}
	
	public function update($table, $id, $fields = array())
	{
		$this->connect->update($table, $id, $fields);
	}
	
	public function delete($table, $fields)
	{
		 if(is_array($fields) && count($fields) === 3){
			
			$operators = array('=', '>', '<', '>=', '<=');
			
			$key = $fields[0];
			$operator = $fields[1];
			$value = $fields[2];
			
			if(in_array($operator, $operators)){
				$sql = "FROM {$table} WHERE {$key} {$operator} ?";
				$this->connect->delete($sql, array($value));
			}
		}
	}
		
	public function operation($table, $columns = '', $equal = '', $or = '', $order = '')
	{
		$op = '';
		
		if(empty($columns)) {
			$select = '* FROM '.$table;
		}else{
			$s = '';
			$x = 1;
			foreach($columns as $column) {
				$s .= $column;
				if($x < count($columns)) {
					$s .= ', ';
				}
				$x++;
			}
			$select = $s. ' FROM '.$table;
		}
		
		if(!empty($equal)) {
			$op .= " WHERE ". $this->operator(array_keys($equal), ' AND ');
		}
		if(!empty($or)) {
			$op .= " OR ". $this->operator(array_keys($or), ' OR ');
		}
		
		if(!empty($order)) {
			$order = ' ORDER BY '.$order;
		}
		
		$sql = "{$select}{$op}{$order}";
		
		$this->connect->select($sql, array_merge(array_values($equal), array_values($or)));
	}
	
	protected function operator($o, $s)
	{
		$x = 1;
		$selector = '';
		foreach($o as $m){
	      	$selector .= str_replace(array(';','DROP','drop','TABLE','table', 'ADD','add','CHANGE','change'),'', $m.' ?');
	       	if($x < count($o)){
				$selector .= "{$s}";
	       	}
	       $x++;
		}
		return $selector;
	}

	public function createDb($name)
	{
		$this->connect->schemaQuery($name);
	}
	
	public function count()
	{
		return $this->connect->count();
	}
	
	public function result()
	{
		return $this->connect->result();
	}
	
}