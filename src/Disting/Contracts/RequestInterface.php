<?php

namespace Disting\Contracts;


interface RequestInterface
{
    public function content($content);

    public function status($status);

    public function isAjax();

    public function input();

}