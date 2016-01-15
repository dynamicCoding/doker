<?php

namespace Disting;

class Environment
{
	
	protected $env;
	
	protected $debug;
	
	protected $alert;
	
	protected $control;
	
	protected $show_error_class;
	
	public function __construct($env, $error = false)
	{
		$this->env = $env;
		$this->debug = $error;
		putenv("entorno=".$env);
	}
	
	public function controllerUrl($is = true)
	{
		$this->control = $is;
	}
	
	public function alertModifyParamsUrl($is = true)
	{
	 	$this->alert = $is;
	}
	 
	 public function showErrorNotFoundClassAndMethod($is = true)
	 {
	 	$this->show_error_class = $is;
	 }
	 
	public function getEnv()
	{
		return $this->env;
	}
	
	public function getDebug()
	{
		return $this->debug;
	}
	
	public function isAlertModifyParams()
	{
		return $this->alert === true;
	}
	
	public function isControlUrl()
	{
		return $this->control === true;
	}
	
	public function isShowErrorClass()
	{
		return $this->show_error_class === true;
	}
}