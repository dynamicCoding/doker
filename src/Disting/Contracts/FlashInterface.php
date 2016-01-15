<?php

namespace Disting\Contracts;

interface FlashInterface {
	public function put($name, $msg);
	
	public function get($name);
}