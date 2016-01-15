<?php

namespace Disting\Validator;

class Input
{
	public static function exists($m = 'post')
	{
		switch($m) {
			case 'post':
				return !empty($_POST) ? true : false;
			break;
			case 'get':
				return !empty($_GET) ? true : false;
			break;
		}
	}
	
	public static function item($item)
	{
		if(!empty($_POST[$item])) {
			return $_POST[$item];
		}elseif(!empty($_GET[$item])) {
			return $_GET[$item];
		}
		return '';
	}
}