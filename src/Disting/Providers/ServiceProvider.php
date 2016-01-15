<?php

namespace Disting\Providers;

use Disting\Providers\ServiceProviderAccess;
use Disting\Providers\ServiceProviderTrait;
use Disting\Providers\Services\TypeService;
use Disting\Http\Level\FRW;
use Disting\Http\Request;


class ServiceProvider extends ServiceProviderAccess
{
	use ServiceProviderTrait;
	
	protected $register;
	
	protected $level;
	
	protected $name;
	
	protected $request;
	
	protected $path;
	
	protected $frw;
	
	protected $content_type;
	
	public function __construct()
	{
		$this->request = new Request;
	}
	
	private function registerName($name, $option)
	{
		$this->register[$name] = $option;
	}
	
	public function path($path)
	{
		$this->path = $path;
	}
	
	public function content($content)
	{
		$this->content_type = $content;
	}
	
	/**
	 * @param puede ser una clase de model, policieso request 
	 * o un aegumento de acceso interno de la clase
	 */
	public function provider($arg)
	{
		if(is_string($arg)) {
			$this->isServiceClass($arg);
		}elseif(is_array($arg)) {
			$this->providerAccess($arg);
		}
	}
	
	protected function typeProvider($ts, $type, $val = null)
	{
		if($ts instanceof TypeService){
			switch($type){
				case 'policies':
					$ts->servicePolicies($val);
					$this->servicePolicies($ts);
				break;
			}	
			
		}elseif($ts instanceof FRW) {
			$this->frw($ts, $type);
		}
	}
	
	
	protected function servicePolicies($service)
	{
		$pl = $service->get('policies');
		if(!is_null($pl)){
			$action = array_reverse($pl->getAction());
			if($action['error_auth']){
				$this->providerAccess($action);
			}
		}
	}
	
	protected function frw(FRW $frw, $access)
	{
		$this->frw = $frw;
		$isAccess = $access["denied"];
		foreach($access as $key => $value){
			switch($key){
				case 'status':
					$frw->setStatus($value);
					$this->registerName('status', $frw);
				break;
				case 'redirect':
					$frw->setRedirect($value);
				break;
				case 'get_path':
					$frw->setPath($access['get_path']);
				break;
				case 'cookie':
					$frw->setCookie($value);
					$this->registerName('cookie', $frw);
				break;
			}
		} 
		
		$this->registerName('frw', $frw);
		if($isAccess == true)
			$frw->level($this->access(), $access);
		else
			$frw->level('false', $access);
		$this->define();
	}
	
	protected function providerStatus($status)
	{
		$this->request->status($status);
	}
	
	protected function providerRedirect($redirect)
	{
		$this->request->redirect($redirect);
	}
	
	protected function providerCookie($cookie) 
	{
		
	}
	
	protected function define()
	{
		$get = $this->check('frw');
		$level = $get->getUnlevel();
		
		$method = $level['internal']['method'];
		$url = $level['internal']['url'];
		$status = $level['internal']['status'];
		$content_type = $level['internal']['Content-Type'];
		
		if($this->content_type !== null){
			$content_type = $this->content_type;
		}
		
		$charset = $level['internal']['charset'];
		$msg = $level['internal']['message'];
		
		if($level['access']['level'] === 0){
			$this->providerStatus($status);
			$this->providerRedirect($get->getRedirect());
		}elseif($level['access']['level'] === 1) {
			
		}
	}
	
	public function check($name)
	{
		if(!isset($this->register[$name])){
			trigger_error('no esta definido '.$name, E_USER_ERROR);
		}
		
		$name = $this->register[$name];
		
		return $name;
	}
}