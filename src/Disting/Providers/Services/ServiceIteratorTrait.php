<?php

namespace Disting\Providers\Services;

use Disting\Providers\Services\ServicePolicies;

trait ServiceIteratorTrait {
	
	protected $each;
	
	protected function setEach($app, $class)
	{
		$this->each[$app] = $class;
	}
	
	protected function getEach($name)
	{
		if(!isset($this->each[$name])){
			trigger_error('offset no definido '.$name, E_USER_ERROR);
		}
		
		$get = get_class_vars(get_class($this->each[$name]));
		
		if($name === 'policies'){
			$get = (new ServicePolicies)->verify($get);
		}
		
			
		return $get !== null ? $get : array();
	}
	
	public function get($name)
	{
		if(array_key_exists($name, $this->each) && isset($this->each[$name])){
			return $this->getEach($name);
		}
	}
}