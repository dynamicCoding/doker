<?php

namespace Disting;

use Handler\HandlerException as DokerException;
use Disting\Filter\Sanitize;

class Bootstrap
{
	protected $controller;
	
	protected $method;
	
	protected $args;
	
	protected $file;
	
	protected $default;
	
	public function __construct($controller, $method)
	{
		try {
			if(!isset($_GET['url'])) {
				throw new \Exception("no esta definido el method get url");
			}
			
			$get = Sanitize::get('url');
			
			if(empty($get)){
				$this->controller = $controller;
			}else{
				$this->controller = ucwords(array_shift($get));
			}
			
			if(empty($get)){
				$this->method = $method;
				$this->default = $method;
			}else{
				$this->method = array_shift($get);
			}
			
			if(preg_match('/([\-])/', $this->method)) {
				$xpl = explode('-', $this->method);
				$one = array_shift($xpl);
				$union = '';
				foreach($xpl as $case){
					$union .= ucwords(mb_substr($case, 0, null, 'UTF-8'));
				}
				$this->method= $one.$union;
			}
			$this->args = $get;
			
			$this->file = '\Http\Controllers\\'.$this->controller.'Controller';
			
		} catch(DokerException $e) {
			die($e->getMessage());
		}
	}
	
	public function getController()
	{
		return $this->controller;
	}
	
	public function getMethod()
	{
		return $this->method;
	}
	
	public function getArgs()
	{
		return $this->args;
	}
	
	public function getClass()
	{
		return $this->file;
	}
	
	protected function getMethodDefault()
	{
		return $this->default;
	}
}