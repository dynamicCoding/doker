<?php

namespace Disting\Validator;

use Disting\Config;
use Disting\Filter\Filter;
use Disting\Filter\Sanitize;
use Disting\Validator\Input;
use Disting\Validator\ErrorValidator;
use Disting\Contracts\ValidatorInterface;
use Disting\Validator\Providers\UserProvider;

class Validator extends ErrorValidator implements ValidatorInterface
{
	protected $method;
	
	protected $item;
	
	protected $config;
	
	protected $pased = false;
	
	public function __construct()
	{
		$config = (new Config)->resources()->load('default_lang');
		$this->config = (new Config)->resources()->load($config->data('lang'));
	}
	
	/**
	 * @param $e escapar caracteres raros
	 */
	protected function scape($e)
	{
		$e = Sanitize::string($e);
		$e = addslashes($e);
		
		return $e;
	}
	
	/**
	 * @param! $method $_POST Y $_GET
	 * @param $rules validaciones
	 */
	public function check($method, $rules)
	{
		$this->method = $method;
		foreach($rules as $item => $rule) {
			$this->item = $item;
			$validation = explode('|', $rule);
			array_map(array($this, 'map'), $validation);
		}
		
		 if(empty($this->errors)) {
			$this->pased = true;
		}
		
		return $this;
	}
	
	/**
	 * @param $item 
	 * @return value $_POST[$item] or $_GET[$item]
	 */
	public function item($item)
	{
		return $this->scape(Input::item($item));
	}
	
	/**
	 * retorn si exite algo por ge oh post
	 */
	public function method($m = 'post')
	{
		return Input::exists($m);
	}
	
	/**
	 * @param $c 
	 * @param $l
	 * verifica si el valor es mayor al proporcionado
	 */
	protected function maxLength($c, $l)
	{
		return strlen($c) > (int)$l ? true : false;
	}
	
	/**
	 * validacion de los campos proporcionado
	 */
	protected function map($v)
	{
		$source = $this->method;
		$item = $this->item;
		
		$check = strstr($v, ':', true) ? strstr($v, ':', true) : false;
		$val = strstr($v, ':') ? substr(strstr($v, ':'), 1) : '';
		
		$get = $this->scape($source[$item]);
		
		if($v === 'required' && empty($source[$item])) {
			
			$this->addErrors(
				$this->replaceAttribute('empty', $item)
			);
			
		}elseif(!empty($source[$item])){
			switch($check) {
				case 'max':
				
					if($this->maxLength($get, $val)) {
						$this->addErrors(
							$this->replaceAttribute('max', $item, $val)
						);
					}
					
				break;
				case 'min':
					
					if($this->minLength($get, $val)) {
						$this->addErrors(
							$this->replaceAttribute('min', $item, $val)
						);
					}
					
				break;
				case 'unique':
					if($this->unique($get, $val)){
						$this->addErrors(
							$this->replaceAttribute('unique', $item, $get)
						);
					}
				break;
			}
		}
		
		if($v === 'valid' && !empty($source[$item])) {
			if(!$this->validEmail($get)) {
				$this->addErrors(
					$this->replaceAttribute('valid', $item)
				);
			}
		}
		
		return $this;
	}
	
	/**
	 * verifica si el valor no es mayor al proporcionado
	 */
	protected function minLength($c, $l)
	{
		return strlen($c) < (int)$l ? true : false;
	}
	
	/**
	 *	comprueba que no exista el mismo valor en la base de datos\
	 */
	protected function unique($data, $table)
	{
		$column = strpos($table, '_') ? substr(strstr($table, '_'), 1) : 'email';
		$table = strpos($table, '_') ? substr(strstr($table, '_', true), 0): $table;
		$user = (new UserProvider)->compruebe($table, $column, $data);
		if($user->count() > 0) {
			return true;
		}
		return false;
	}
	
	/**
	 * verifica que el email sea valido
	 */
	protected function validEmail($valid)
	{
		return Filter::email($valid);
	}
	
	/**
	 * @return mensaje de error
	 */
	protected function replaceAttribute($key, $attr, $val = null)
	{
		$get = $this->config->data($key);
		$rpl = str_replace(array(':attribute', ':value'), array($attr, $val), $get);
		
		return $rpl;
	}
}