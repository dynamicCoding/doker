<?php

namespace Disting;

class ControllerUrl
{
	protected $uri;
	
	public function __construct($c, $n, $m, $a)
	{
		$url = class_exists($c) ? '/'.strtolower($n).'/'.$m : uniqid().'/'.$m;
		if(is_array($a) && !empty($a)) {
			$x = 1;
			$url .= '/';
			foreach($a as $arg) {
				$url .= $arg;
				if($x < count($a)) {
					$url .= '/';
				}
				$x++;
			}
		}
		
		$this->url = $url;
	}
	
	public function isEqual()
	{
		return $this->verifyUrl();
	}
	
	protected function verifyUrl()
	{
		$_replace = substr(strstr($_SERVER['REQUEST_URI'], '-'), 0);
		$_get = ucwords(substr($_replace, 1));
		$server = str_replace($_replace, $_get, $_SERVER['REQUEST_URI']);
		
		return $this->url !== $server && $_SERVER['REQUEST_URI'] !== '/' ?:false;
	}
	
	public function getUrl()
	{
		return $this->url;
	}
}