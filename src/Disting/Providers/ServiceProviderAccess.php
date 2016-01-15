<?php

namespace Disting\Providers;

use Disting\Providers\Services\TypeService;
use Disting\Http\Level\FRW;

class ServiceProviderAccess
{
	public function providerAccess(array $access = array())
	{
		if(isset($access['name'])){
			foreach($access as $key => $value){
				$this->typeProvider(new TypeService, $key, $value);
			}
		}elseif(isset($access['denied'])){
			if(isset($this->path)){
				$access = array_merge($access, ['get_path' => $this->path]);
			}
			$this->typeProvider(new FRW, $access);
		}
	}
	
	public function access()
	{
		return 'access';
	}
}