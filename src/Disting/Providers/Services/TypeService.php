<?php

namespace Disting\Providers\Services;

use Disting\Providers\Services\ServiceIteratorTrait;

class TypeService
{
	
	use ServiceIteratorTrait;
	
	protected $policies;
	
	protected $eloquent;
	
	protected $rules;
	
	
	/**
	 * @param $class nombre de la clase para denegar el acceso 
	 */
	public function servicePolicies($class)
	{
		$this->setEach('policies', $class);
	}
}