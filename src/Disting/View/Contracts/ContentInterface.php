<?php

namespace Disting\View\Contracts;


interface ContentInterface
{
    public function set($key, $val);

    public function optimizer($view);

    public function resources($view);
}