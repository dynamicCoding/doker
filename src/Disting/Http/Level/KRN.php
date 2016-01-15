<?php

namespace Disting\Http\Level;

use Disting\Http\Level\FRW;
use Disting\Session\Session;

class KRN
{
	protected $env = false;
	
	protected $frw;
	
	public function __construct(FRW $frw)
	{
		$this->frw = $frw;
		$this->auth = new Session;
	}
	
	public function access($t, $u)
	{
		$this->push($t, $u);
	}
	
	 /**
	 *  bloquear el accesso o acceder 
	 */
	protected function push($t, $u)
	{
		$firewall = $this->frw->__ini__frw();
		
		$firewall['uri'] = $u;
		
		$tk = $firewall[$t];
		$divide = str_split($tk, 1);
		$length = mb_strlen($tk, 'UTF-8');
		$num = '';
		for($i = 0; $i < $length; ++$i){
			$num .= $length*($i - 4);
			$pk = pack('Hs*', $tk);
		}
		
		$increment_v = str_replace('xxx', substr($num, 1), $tk);
		
		$session = $firewall['implements']['session_name'];
		$cookie = $firewall['implements']['cookie_name'];
			
		if($t === 'token_novalid'){
			$this->frw->codeLevel($increment_v);
			$this->frw->unLevel($u);
			
			//valor de la session
			$val_s = $firewall['refresh'];
			//error de acceso
			$val_c = $this->frw->getCodeUnlevel();			
			
			if(!$this->auth->exists($session)){
				 $this->auth->start($session,base64_encode($val_s));
			}
			
		}else{
			if($this->auth->exists($session)){
				$this->auth->remove($session);
			}
			$this->frw->codeLevel($increment_v); 
			$this->frw->unLevel($u);
			
			$this->env = true;
		}
	} 
	
	public function getEnv()
	{
		return $this->env;
	}
}