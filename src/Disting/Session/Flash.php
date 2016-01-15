<?php

namespace Disting\Session;

use Disting\Session\Session;
use Disting\Contracts\FlashInterface;

class Flash implements FlashInterface
{
	protected $flash;
	
	public function __construct()
	{
		$this->flash = new Session;
	}
	
	public function put($name, $msg)
	{
		if(!$this->exists($name)) {
			$this->flash->start($name, $msg);
		}else{
			$this->flash->remove();
		}
	}
	
	public function get($name)
	{ 
		$get = $this->flash->get($name);
		$this->flash->remove($name);
		return $get;
	}
	
	public function exists($name)
	{
		return $this->flash->exists($name);
	}
}