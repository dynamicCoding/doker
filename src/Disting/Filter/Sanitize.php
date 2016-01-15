<?php

namespace Disting\Filter;

use Disting\Filter\Filter;

class Sanitize
{
	public static function float($float)
	{
		 if(Filter::float($float)) {
		 	return filter_var($float, FILTER_SANITIZE_NUMBER_FLOAT);
		 }
		 
		 return false;
	}
	
	public static function string($s)
	{
		return htmlentities(filter_var($s, FILTER_SANITIZE_STRING), ENT_QUOTES);
	}
	
	public static function get($get)
	{
		$var = filter_input(INPUT_GET, $get, FILTER_SANITIZE_URL);
		
		if($var !== false){
			$xpl = explode("/", $var);
			
			return array_filter($xpl);
		}else{
			return false;
		}
	}
	
	public static function post($post)
	{
		$var = filter_input(INPUT_POST, $post, FILTER_SANITIZE_URL);
		
		return $var;
	}
}