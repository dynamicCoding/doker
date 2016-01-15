<?php

namespace Disting\Validator;

class ErrorValidator
{
	protected $errors = array();
	
	protected function addErrors($errors)
	{
		$this->errors[] = $errors;
	}
	
	public function isValid()
	{
		return $this->pased;
	}
	
	public function errors()
	{
		return $this->errors;
	}
}