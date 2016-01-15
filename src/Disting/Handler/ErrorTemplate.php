<?php

namespace Disting\Handler;

use Disting\Handler\HandlerException; 
use Disting\Base;

class ErrorTemplate extends Base
{
	public function error(HandlerException $e) 
	{
		$code  = $e->getCode();
		$msg = $e->getMessage();
		$file = $e->getFile();
		$line = $e->getLine();
		$trace = str_replace(array('#', "\n"), array('<div>#', '</div>'), $e->getTraceAsString());
		
		$this->load->viewPath('src/Disting/Handler/');
		$this->load->view('error')
		->vars([
			"error" 			=> 	$e, 
			"title" 			=> 	"error en la aplicacion",
			'title_header' 		=> 	'hubo un error en la aplicacion',
			'code' 				=> 	$code,
			'msg' 				=> 	$msg,
			'file' 				=> 	$file,
			'line'				=> 	$line,
			'trace' 			=> 	$trace
		]);
	}
}