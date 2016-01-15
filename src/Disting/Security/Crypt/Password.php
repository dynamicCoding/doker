<?php

namespace Disting\Security\Crypt;

class Password
{
	const BCRYPT = PASSWORD_BCRYPT;
	
	const P_DEFAULT = PASSWORD_DEFAULT;
	
	protected $cost = [
		'cost'	=> 10
	];
	
	protected $config;
	
	protected $hash;
	
	public function hash($password, $bcrypt = self::BCRYPT, $salt = null)
	{
		if($salt !== null && !is_bool($salt)) {
			trigger_error('el 3 parametro debe de ser un bool', E_USER_ERROR);
			exit;
		}
		 
		$cost = $salt == true ? $this->addSalt() : $this->cost;
		
		if($bcrypt === self::BCRYPT){
			$this->hash = password_hash($password, $bcrypt, $cost);
		}else{
			$this->hash = password_hash($password, $bcrypt);
		}
		
		return $this;
	}
	
	protected function addSalt()
	{
		$this->cost['salt'] = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
		
		return $this->cost;
	}
	
	public function verify($password, $hash)
	{
		if($this->isHashError($password, $hash)) {
			return true;
		}
		
		return false;
	}
	
	protected function isHashError($p, $h)
	{
		return password_verify($p, $h);
	}
	
	public function get()
	{
		return $this->hash;
	}
}