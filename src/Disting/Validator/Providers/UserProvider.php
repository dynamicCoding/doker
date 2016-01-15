<?php

namespace Disting\Validator\Providers;

use Ilum\Ilum;

final class UserProvider
{
	protected $ilum;
	
	protected $count = 0;
	
	public function __construct()
	{
		$this->ilum = new Ilum;
	}
	
	public function compruebe($table, $key, $value)
	{
		$user = $this->ilum->select($table)
					->equal($key.' =', $value)
					->execute();
		
		if($user->count() > 0){
			$this->count = $user->count();
		}
		
		return $this;
	}
	
	public function count()
	{
		return $this->count;
	}
}