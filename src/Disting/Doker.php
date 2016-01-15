<?php

namespace Disting;

use ReflectionClass;
use Disting\Base;
use Disting\Environment;
use Disting\Handler\HandlerError;
use Disting\ControllerUrl;

class Doker extends Base
{
	protected $debug = true;
	
	protected $controller_uri;
	
	public function __construct(Bootstrap $bts, HandlerError $error)
	{
		parent::__construct();
		$debug = $error->isDebug();
		 switch(getenv("entorno")) {
		 	case 'development':
		 	 if($debug == false) {
				ini_set("display_errors", "Off");
			}else {
				set_error_handler(array($error, 'inicializeError'));
			}
		 	break;
		 	case 'production':
		 		ini_set("display_errors", "Off");
		 	break;
		 }
		 $this->control($bts, $error);
	}
	
	protected function control($bts, $env)
	{
		$name = $bts->getController();
		$controller = $bts->getClass();
		$method = $bts->getMethod();
		$args = $bts->getArgs();
		
		if($env->environment()->isShowErrorClass()) {
			if(!$this->existsClass($controller)) {
				trigger_error("error al encontrar el controlador {$controller}", E_USER_ERROR);
				exit;
			}elseif(!$this->existsMethod($controller, $method) || !$this->existsMethod($controller, $method.'Post')) {
				trigger_error("no se encontro el metodo {$method} en el controlador {$controller}", E_USER_WARNING);
			}
			return;
		}
		
		if($env->environment()->isControlUrl()) {
			$m = $this->existsMethod($controller, $method) || $this->existsMethod($controller, $method.'Post') ? $method : uniqid();
			$this->controller_uri = new ControllerUrl(
				$controller, $name, $m, $args
			);
			
			if($this->controller_uri->isEqual()) {
				header('HTTP/1.1 404 Page Not Fount');
				$this->load->view('errors.404', true);
				exit;
			}
		}
		
		$this->addParamsMethod($controller, $method, $args, $env);
	}
	
	protected function existsClass($class)
	{
		return class_exists($class) ? true : false;
	}
	
	protected function existsMethod($c, $m)
	{
		return method_exists($c, $m) ? true : false;
	}	
	
	/**
	 * @param $controller controlador
	 * @param $method method
	 * @param $args argumentos 
	 * @param $env
	 */
	protected function addParamsMethod($controller,$method,$args, $env)
	{
		$controller = new $controller;

		if(method_exists($controller, $method)){
			$method = $method;
		}else{
			$method = $method.'Post';
		}
		
		$rf = new ReflectionClass($controller);
		
		$getMethod 	= 	$rf->getMethod($method); 
		$count 			= 	$getMethod->getNumberOfParameters();
		$params 		= 	$getMethod->getParameters();
		
		if($env->environment()->isAlertModifyParams()) {
		 	$this->modifyArgs($params, $args, $count, $env);
		}
			
		$add = array();
		
		foreach($params as $param){
			if($param->getClass() !== null){
				$paramClass = $param->getClass()->name;
				$call = "\\".$paramClass;
				$add[] = new $call;
			}else{
				$param = $param->name;
				if($param === 'closure'){
					$add[] = \Closure;
				}
			}
		}
		
		$this->service->path($this->controller_uri->getUrl());
		
		$this->call(
			$controller, $method, array_merge($args, $add)
		);
	}
	
	protected function modifyArgs($p, $a,$c, $e)
	{
		if(count($a) > 0) {
			switch(getenv('entorno')) {
				case 'development':
				case 'production':
					if (count($a) > 0 && count($p) === 0) {
						 if($e->isDebug() == true){
							trigger_error('
								modificacion de la url', E_USER_ERROR
							);
							exit;
						}else {
							$this->load->view('errors.modifyurl', true);
							exit;
						}
					}
					foreach($p as $param) {
						if(!$param->getClass() &&$param->name) {
							if(count($param->name) !== count($a)) {
								if($e->isDebug() == true){
									trigger_error('
										modificacion de la url', E_USER_ERROR
									);
									exit;
								}else {
									$this->load->view('errors.modifyurl', true);
									exit;
								}
							}
						}
					}
				break;
			}
		}
	}
	
	protected function call($controller, $method, $args)
	{
		if(method_exists($controller, $method) && stripos($method, 'Post') == true){
		 	
		 	if(isset($_POST) && !empty($_POST)){
		 	
				call_user_func(
					array($controller, $method. 'Post'), $args
				);
				
			}
		 	
		}else{			
			
				call_user_func_array(
					array($controller, $method), $args
				);
			
		}
	}
}