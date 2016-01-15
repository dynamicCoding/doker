<?php

namespace Disting;


class Config
{
	protected $data;
	
	protected $default;
	
	protected $dir;
	
	public function resources()
	{
		$this->dir = APP_PATH.'/resources/lang/';
		
		return $this;
	}
	
	public function load($file)
	{
		$c = !empty($this->dir) ? $this->dir.$file.'.php' : APP_PATH.'/Config/'.$file.'.php';
		
		if(!file_exists($c)){
			trigger_error("error al encontrar el archivo {$file}", E_USER_WARNING);
		}
		
		$this->data = require $c;
		
		return $this;
	}
	
	public function data($key, $default = null)
	{
		$this->default = $default;
		
		$segments = explode('.', $key);
		$data = $this->data;
		
		foreach($segments as $segment){
			if(isset($data[$segment])){
				$data = $data[$segment];
			}else{
				$data = $this->default;
				break;
			}
		}
		return $data;
	}
}