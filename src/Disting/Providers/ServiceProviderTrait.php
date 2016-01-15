<?php

namespace Disting\Providers;

use ReflectionClass;
use Disting\Providers\Services\TypeService;

trait ServiceProviderTrait {
	
	/**
	 * @param $service nombre de la clase | definicion de nombres de tipo de 
	 * service requerido
	 */
	protected function isServiceClass($service)
	{
		if(!class_exists($service)){
			trigger_error('verifca que sea una clase existente dada '. $service,E_USER_ERROR);
		}
		
		$rf = new ReflectionClass($service);
		$space = $rf->getNamespaceName();
		$name_class = $rf->getName();
		switch($space) {
			case 'App\Denied':
				$t = 'policies';
				$policies = '\\'.$name_class;
				$name_class = new $policies;
				$this->registerName('policies', $policies);
			break;
		}
		return $this->typeProvider(new TypeService, $t, $name_class);
	}
}