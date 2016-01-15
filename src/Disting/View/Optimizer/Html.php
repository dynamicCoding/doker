<?php

namespace Disting\View\Optimizer;

use Disting\View\Contracts\HtmlInterface;

class Html implements HtmlInterface
{

    protected $path_js;

    protected $path_css;

    protected $path_img;

    public function css($css = '')
    {
         return $this->actionAssets($css, '<link rel="stylesheet" href="/', $this->path_css, '.css"', '>');
    }

    protected function actionAssets($get = '', $open, $path, $ext, $close = '')
    {
        $tag = '';
        $folder = APP_PATH.'/public/'.$path;
        if(empty($get)){
            $scan = scandir($folder);
            foreach($scan as $file){
                if(is_readable($folder.$file) && strlen($file) > 3){
                    $file = str_replace(str_replace('"', '', $ext), '', $file);
                    $tag .= "\t".$open.$path.$file.$ext.$close."\n";
                }
            }
        }else{
            if(is_readable($folder.$get.str_replace('"', '', $ext))){
                $tag .= "\t".$open.$path.$get.$ext.$close."\n";
            }
        }
        return $tag;
    }

    public function js($js = '')
    {
        return $this->actionAssets($js, '<script src="/', $this->path_js, '.js"', '></script>');
    }

    public function linkTo($url, $name, array $options = array())
    {
        return $this->actionTags('<a href="'.$url.'"', $options, '>'.$name.'</a>');
    }

    protected function actionTags($html,  $options = array(), $close)
    {
        $tag = "\t".$html;
        foreach ($options as $key => $value) {
            $tag .= ' '.$key.'="'.$value.'"';
        }
        $tag .= $close."\n";

        return $tag;
    }

    public function input($type, $name, array $options = array())
    {
        return $this->actionTags('<input type="'.$type.'" name="'.$name.'"', $options, '>');
    }

    public function paths($css, $js, $img)
    {
        $this->path_css = $css;
        $this->path_js = $js;
        $this->path_img = $img;
    }

    public function image($name, array $options = array())
    {
        return $this->actionTags('<img src="/'.$this->path_img.$name.'"', $options,'>');
    }
}