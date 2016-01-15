<?php

namespace Disting\Providers\Services;

use Disting\Session\Session;
use Disting\Providers\Contracts\ServiceVerifyInterface;

class ServicePolicies implements ServiceVerifyInterface
{
	
	protected $auth;
	
	protected $action;
	
	public function __construct()
	{
		$this->auth = new Session;
	}
	
	public function verify($verify)
	{
		foreach($verify as $name => $rules){
			if(!is_array($rules)){
				
				if($name == 'denied'){
					if($rules == true){
						$this->action['denied'] = $rules;
					}else{
						$this->action['denied'] = $rules;
					}
				}
				
				if($name == 'status'){
					$this->action['status'] = $rules;
				}
				
				if($name === 'redirect'){
					$this->action['redirect'] =  $rules;
				}
				
				if($name == 'page_error'){
					$this->action['render'] = $rules;
				}
				
			}else {
				foreach($rules as $auth) {
					if($name ===  'auth'){
						$this->authExists($auth);
					}
				}
			}
		}
		
		return $this;
	}
	
	protected function authExists($auth)
	{
		if($this->auth->exists($auth)) {
			$this->action['error_auth'] = true;
		}else{
			$this->action['error_auth'] = false;
		}
	}
	
	public function getAction()
	{
		return $this->action;
	}
}