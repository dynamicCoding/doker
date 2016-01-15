<?php

namespace Disting\View\Optimizer;

use Disting\View\Optimizer\Html;

class Functions
{
	protected $content;

    protected $path;

    protected $vars;

    protected $yield;

    protected $block;

    protected $register;

    protected $name = array();

    protected $get;

    protected $html;

    public function __construct()
    {
        $this->html = new Html;
    }

    protected function registerFunctions()
    {
        return [
            '/(\{\!)/',
            '/(\!\})/',
            '/(\{\%)/',
            '/(\%\})/',
            '/(\{\#)/',
            '/(\#\})/',
            //
            '/(\@)/',
            '/(\)\;)/',
            '/(\)\:)/',
            '/else:/',
            '/endif\;/',
            '/endforeach\;/',
            '/([\'|\"]\)\;)/',
            '/([\'|\"]\)\:)/',
            //
            '/extend/',
            '/yield/',
            '/block(\(\')/',
            '/endblock/',
            '/(link_to\(\')/',
            '/(link_tag_css\()/',
            '/script_tag(\()/',
            '/input(\(\')/',
            '/image_tag\(\'/'
        ];
    }

    protected function replaceFunctionInMethod()
    {
        return [
            '<?php echo ',
            '?>',
            '<?php',
            '?>',
            '<?php /*',
            '*/ ?>',
            //
            '<?php ',
            '); ?>',
            '): ?>',
            'else: ?>',
            'endif; ?>',
            'endforeach; ?>',
            '\');',
            '\');',
            //
            '$this->extend',
            '$this->start(); $this->yields',
            '$this->endYield(); $this->block(\'',
            '$this->endBlock(); ?>',
            '$this->html->linkTo(\'',
            '$this->html->css(',
            '$this->html->js(',
            '$this->html->input(\'',
            '$this->html->image(\''
        ];
    }

    public function dir($view)
    {
        $this->path = $view['view'];
        $this->html->paths($view['css'], $view['js'], $view['img']);
    }

    public function content($c)
	{
		$this->content = $c;
	}
	
	public function set($vars = array())
	{
		$this->vars = $vars;
	}
	
	public function render()
	{
		extract($this->vars);
		$pattern = $this->registerFunctions();
		$replace = $this->replaceFunctionInMethod();
		$preg = preg_replace($pattern, $replace, $this->content);
        eval("?>{$preg}<?php ");
	}

    protected function start()
    {
        ob_start();
    }

    protected function extend($file)
    {
        $path = $this->path.str_replace('.', '/', $file);
        if(file_exists($path.'.redis.php')) {
            ob_start();
            include($path.'.redis.php');
            $content = ob_get_contents();
            ob_end_clean();
            $this->content($content);
            $this->render();
        }
    }

    function yields($name)
    {
        $this->name[$name] = $name;

        $this->name($name);
    }

    protected function endYield()
    {
        $this->yield = ob_get_contents();
        ob_end_clean();
        $this->get = array_keys($this->register);
    }

    protected  function name($name)
    {
        $this->register[] = $name;
    }

    function block($name)
    {
        if(!$this->key($name)){
            trigger_error('no esta definido '.$name,E_USER_ERROR);
            exit;
        }

        $this->get[$name] = $this->yield;

        $this->get = $this->get[$name];

        $this->start();
    }

    function endBlock()
    {
        $block = ob_get_contents();
        ob_end_clean();
        echo sprintf("%s%s", $block, $this->get);
    }

    protected function key($name)
    {
        return array_key_exists($name, $this->name);
    }

}