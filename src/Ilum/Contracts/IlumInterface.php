<?php

namespace Ilum\Contracts;

interface IlumInterface {
	
	public function select($table, $column = array());
	
	public function insert($table, $fields = array());
	
	public function update($table, $id, $fields = array());
	
	public function delete($table, $id);
	
	public function ors($key, $val);
	
	public function equal($key, $val);
	
	public function execute($orderBy = '');
	
	public function save();
	
	public function result();
	
}