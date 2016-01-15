<?php

namespace Disting\Session;

use Crypt\MCrypt;

class Session
{
	protected $crypt;
	
	protected $register;
	
	/*public function crypt($crypt = false)
	{
		$this->crypt = $crypt;
	}*/
	
	protected function registerName($name, $val)
	{
		$this->register[$name] = $val;
	}
	
	protected function blockSession($array)
	{
		foreach($array as $key => $value) {
			$this->addSession($key, $value);
		}
	}
	
	public function start($key, $value = '')
	{
		if($this->isArray($key)){
			$this->registerName('doker.array.session', $key);
		}
		
		if($this->exists($key)) {
			echo "la sesion con el nombre {$key} ya existe";
			return;
		}
		
		$this->conditions($key, $value);
	}
	
	protected function isArray($is)
	{
		return is_array($is) ? true : false;
	}
	
	protected function conditions($key, $val = '')
	{
		if(isset($this->register['doker.array.session'])) {
			$this->blockSession($key);
		}else{
			$this->addSession($key, $val);
		}
	}
	
	protected function isCryptSession()
	{
		return $this->crypt === true ?: false;
	}
	
	protected function addSession($name, $value)
	{
		if($this->isCryptSession()) {
			$crypt = new MCrypt('auth_crypt', $value);
			$_SESSION[$name] = $crypt->encrypt();
		}
		
		$_SESSION[$name] = $value;
	}
	
	public function get($name)
	{
		return $this->getSession($name);
	}
	
	protected function getSession($name)
	{
		if(!$this->exists($name)) {
			echo "la sesion con el nombre {$name} no existe";
			return;
		}
		
		if($this->isCryptSession()) {
			$crypt = new MCrypt('auth_crypt', $_SESSION[$name]);
			return $crypt->decrypt();
		}
		
		return $_SESSION[$name];
	}
	
	public function exists($name)
	{
		return isset($_SESSION[$name]) ? true : false;
	}
	
	public function remove($name)
	{
		$this->unsetSession($name);
	}
	
	protected function unsetSession($n)
	{
		unset($_SESSION[$n]);
	}
}