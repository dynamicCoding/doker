<?php

namespace Disting\Session;

use Disting\Session\Session;
use Disting\Validator\Input;

class Token
{
	 protected $length;
	
	protected $hash = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	
	protected $session;
	
	public function __construct()
	{
		$this->session = new Session;
	}
	
	public function crsf()
	{
		$this->length = strlen($this->hash);
		$rand = '';
		for($x = 0; $x < $this->length; $x++){
			$rand .= $this->hash[rand(0, $this->length - 1)];
		}
		$unique = md5(uniqid().$rand);
		if($this->session->exists('CRSF_TOKEN') === false){
			 $this->session->start('CRSF_TOKEN', $unique);
		}
			
		return $this->session->get("CRSF_TOKEN");
	}
	
	public function isCrsf($token)
	{
		if($this->session->exists('CRSF_TOKEN') && $this->session->get('CRSF_TOKEN') === Input::item($token)){
			$this->session->remove("CRSF_TOKEN");			
			return true;
		}else{
			$this->session->remove("CRSF_TOKEN");			
			return false;
		}
	}
}