<?php

namespace Disting\Contracts;

interface ValidatorInterface {
	
	public function check($method, $rules);
	
	public function errors();
	
}