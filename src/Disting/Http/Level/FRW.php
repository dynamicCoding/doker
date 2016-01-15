<?php

namespace Disting\Http\Level;

use Disting\Http\Level\KRN;

class FRW 
{
	const LEVEL_ACCESS = 0;
	
	const LEVEL_NO_ACCESS = 1;
	
	protected $path;
	
	protected $redirect;
	
	protected $status;
	
	protected $access;
	
	protected $hex = 'x10x12';
	
	protected $callable;
	
	private $code_access = 'ac-994-z10xac';
	
	private $code_noaccess = 'ac-139-x14ee';
	
	protected $level;
	
	protected $implement;
	
	private $_unlevel;

	public function __construct()
	{
		$this->krn = new KRN($this);
	}
	
	public function level($level, $nd)
	{
		$levels = $level === 'access' ? $this->setAccess(true) : $this->setAccess(false);
		
		$this->internal($nd);
	}
	
	public function defineLevelAccess($level)
	{
		$this->level = $level;
	}
	
	private function internal($nd)
	{ 
		$path = $this->accessPath();
		
		if(!$this->isAccess()){
			$this->_unlevel['access'] = ['level' => FRW::LEVEL_NO_ACCESS, 'path_denied' => $path];
			$this->krn->access('token_novalid', $path);
		}else{
			$this->_unlevel['access'] = ['level' => FRW::LEVEL_ACCESS, 'path_denied' => false];
			$this->krn->access('token_valid', $path);
		}
	}
	
	/**/
	/**
	 * @param $token token de acceso o denegacion
	 */
	public function codeLevel($token = '')
	{
		$this->level = $token;
	}
	
	/**
	 * @param $url url de donde se ejecuta
	 */
	public function unLevel($url = '/')
	{
		if(empty($this->level) || strstr($this->level,'x5')){
			$dng = [
				'next' 	=> false,
				'code_access' => $this->code_noaccess,
				'msg' => 'acceso denegado',
				'url' => $url,
				'status' => $this->status
			];
			
			$this->issues($dng);
		}
		
		if(strstr($this->level, 'x10')){
			$data = [
				'next' => true,
				'code_access' => $this->code_access,
				'msg' => 'acceso permitido',
				'url' => $url,
				'status' => $this->status
			];
			
			$this->issues($data);
		}		
	}
	
	/**
	 * problema con verificacion de datos o no
	 */
	public function issues($data)
	{
		$capa = $data['next'] ? $data : array();
		$this->accessAuthorize(
				$data['next'], $data['url'], $data['status'], 
				$data['code_access'], $data['msg']
		);
		$this->_unlevel['regex_wrapper'] = '#<'. $this->level . '>#i';
	}
  	
    /** 
	 * @param $access bool
	 * @param $url
	 * @param $code
	 * @param $msg
	 */
	protected function accessAuthorize($access, $url, $status, $code, $msg)
	{
		$this->callable = function() use($access,$url,$status,$code,$msg){
			$code = md5($code);
			$this->_unlevel['internal'] = [
						'method'				=> 'GET',
						'url' 					=> $url,
						'status'				=> $status,
						'Content-Type' 	=> 'text/html', // ;charset=utf-8
						'charset'				=> 'utf-8',
						'HTTP_ACCEPT'	=> 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
						'accept-app' 		=> $access,
						'code-app' 			=> '10$x'.$code,
						'message' 			=> $msg
				];
		};
		
		call_user_func($this->callable, '');
	}
	
	/**
	 * obtener el codigo de acceso
	 */
	public function getCodeUnlevel()
	{ 
		preg_match('/[a-zA-Z-0-9]+/', $this->_unlevel['regex_wrapper'], $m);
		
		foreach($m as $cdc){
			return $cdc;
		}
	}
	
	public function getUnlevel()
	{
		return $this->_unlevel;
	}
	
	/**/
	
	public function setStatus($status)
	{
		$this->status = $status;
	}
	
	public function setPath($path)
	{
		$this->path = $path;
	}
	
	public function setRedirect($redirect)
	{
		$this->redirect = $redirect;
	}
	
	protected function accessPath()
	{
		return $this->path;
	}
	
	/** 
	 * @param $access bool
	 */
	public function setAccess($access)
	{
		$this->access = $access;
	}
	
	public function setCookie($cookie)
	{
		$this->cookie = $cookie;
	}
	
	public function isAccess()
	{
		return $this->access === true;
	}
	
	public function getAccess()
	{
		return $this->access;
	}
	
	public function __ini__frw()
	{
		$frw['implements'] = [
		 	'session_name' => 'frw_access',
			'cookie_name'  => 'frw_c_access'
		];
		
		$frw['verify'] = [
			'exists' 	 => false,
			'type_data' => null,
			'this_exists' => [
				$frw['implements']['session_name'],
				$frw['implements']['cookie_name']
			]
		];
		
		$frw['token_valid'] = 'frw-x20-x10-xxx';
		
		$frw['token_novalid'] = 'frw-x5-x15-xxx';
		
		$frw['uri'] = '';
		
		$frw['refresh'] = uniqid();
		
		return $this->implement = $frw;
	}
	
	public function getRedirect()
	{
		return $this->redirect;
	}
}