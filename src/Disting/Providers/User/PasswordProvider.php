<?php

namespace Disting\Providers\User;

use Ilum\Ilum;
use Disting\Config;
use Disting\Security\Crypt\Password;

class PasswordProvider extends Password
{
	protected $db;
	
	protected $config;
	
	protected $password;
	
	protected $result = false;
	
	protected $success = false;
	
	protected $count = 0;
	
	public function __construct()
	{
		$this->db = new Ilum;
		$this->config = (new Config)->load('CompruebeHashDbConfig');
	}
	
	protected function selectUser($value)
	{
		$table = $this->config->data('table');
		$id = $this->config->data('column_table.id') !== null ? $this->config->data('column_table.id') : '';
		$email = $this->config->data('column_table.email');
		$password = $this->config->data('column_table.password');
		$this->password = $password;
		
		$user = $this->db
						->select($table, [$id, $email, $password])
						->equal($email.' =', $value)
						->execute();
						
		if($user->count() > 0){
			$this->count = $user->count();
		}
		
		return $user;
	}
	
	public function password($pass, $email)
	{
		if(!$this->isEmail($email)){
			trigger_error('ingresar el valor de un email'); exit;
		}
		
		$user = $this->selectUser($email);
		$this->validatePassword($pass, $user);
		
		return $this;
	}
	
	protected function isEmail($e)
	{
		return strpos($e, '@');
	}
	
	protected function validatePassword($pass, Ilum $user)
	{
		if($this->getCount() != 0) {
			foreach($user->result() as $get) {
				if($this->verify($pass, $get->{$this->password})) {
					$this->success = true;
				}
				$this->id = isset($get->id) ? $get->id : '';
			}
			$this->result = true;
		}
	}
	
	public function passwordError()
	{
		return $this->success;
	}
	
	public function emptyResult()
	{
		return $this->result;
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	protected function getCount()
	{
		return $this->count;
	}
}