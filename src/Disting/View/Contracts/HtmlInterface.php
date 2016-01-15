<?php

namespace Disting\View\Contracts;


interface HtmlInterface
{
    public function css($css = '');

    public function js($js = '');

    public function linkTo($url, $name, array $options = array());

    public function input($type, $name, array $options = array());

    public function image($name);

    public function paths($css, $js, $img);
}