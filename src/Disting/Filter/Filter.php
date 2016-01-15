<?php

namespace Disting\Filter;

class Filter
{
	public static function email($email)
	{
		if(strpos($email, '@') === false && filter_var($email, FILTER_VALIDATE_EMAIL)){
			return false;
		}
		
		$e = explode('@', $email);
		
		if(function_exists('checkdnsrr')) {
			$email = checkdnsrr(array_pop($e), 'MX') ? $email : false;
		}
		
		return $email;
	}
	
	public static function float($f)
	{
		if(filter_var($f, FILTER_VALIDATE_FLOAT) && is_float($f)) {
			return $f;
		}
		return false;
	}
	
	public static function numeric($num)
	{
		if(is_int($num) && filter_var($num, FILTER_VALIDATE_INT)){
			return $num;
		}
		return false;
	}
	
	public static function url($url)
	{
		if(filter_var($url, FILTER_VALIDATE_URL)){
			return $url;
		}
		return '';
	}
}