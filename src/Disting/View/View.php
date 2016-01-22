<?php

namespace Disting\View;

use Disting\View\Optimizer\Content;

class View
{
	protected $path = '/Config/';
	
	protected $resources = array();
	
	protected $firts = null;
	
	protected $content;

    protected $vars = null;

    private $file;
	
	protected $extends = '.redis.php';
	
	public function __construct() {
		$config = require APP_PATH.$this->path . 'Resources.php';
		$this->viewPath($config['view']);
		$this->cssPath($config['assets']['css']);
		$this->jsPath($config['assets']['js']);
		$this->imagePath($config['assets']['img']);
		
		$this->content = new Content();
	}
	
	public function cssPath($css) {
		$this->resources['css'] = $this->withReplace($css);
	}
	
	public function jsPath($js) {
		$this->resources['js'] = $this->withReplace($js);
	}

	public function imagePath($image) {
		$this->resources['img'] = $this->withReplace($image);
	}

	public function viewPath($path) {
		$file = APP_PATH.'/'.$this->withReplace($path);
		if(file_exists($file)){
			$this->resources['view'] = $file;
		}else{
			$this->resources['view'] = $this->withReplace($path);
		}
	}
	
	public function view($view, $include = null) {
		$view = $this->resources['view'].$this->withReplace($view);
		$view = file_exists($view.'.php') ? $view.'.php' : $view.$this->extends;

		if(!file_exists($view)) {
			trigger_error('error al encontrar el archivo '.$view, E_USER_ERROR);
		}

		if($this->firts === null && is_null($include)) {
            $this->firts = true;
		}else{
            $this->compiler($view);
		}

		$this->file = $view;
		
		return $this;
	}
	
	public function vars(array $vars) {
		foreach($vars as $key => $val) {
			$this->content->set($key, $val);
		}

		if($this->firts === true) {
			$this->compiler($this->file);
		}else{
            $this->vars = true;
            $this->firts = false;
		}

	}
	
	protected function compiler($view) {
        $this->content->resources($this->resources);
		$this->content->optimizer($view);
	}
	
	protected function withReplace($rpl) {
		return str_replace('.', '/', $rpl);
	}
}



