<?php

namespace Disting\View\Optimizer;

use Disting\View\Contracts\ContentInterface;
use Disting\View\Optimizer\Functions;

class Content implements ContentInterface
{
	protected $extends = '.redis.php';
	
	protected $vars = array();
	
	protected $functions;
	
	public function __construct() {
		$this->functions = new Functions;
	}
	
	public function set($key, $val) {
		$this->vars[$key] = $val;
	}
	
	public function optimizer($view) {	
		ob_start();
		extract($this->vars);
		include($view);
		if(strpos($view, $this->extends)) {
			$content = ob_get_contents();
			ob_get_clean();
			$this->functions->set($this->vars);
			$this->functions->content($content);
			$this->functions->render();
		}else{
			$content = ob_get_contents();
			ob_get_clean();
			echo $content;
		}
	}

    public function resources($view)
    {
        $this->functions->dir($view);
    }
}