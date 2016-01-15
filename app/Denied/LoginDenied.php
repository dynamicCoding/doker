<?php

namespace Denied;

class LoginDenied
{
	public $auth = ["email"];
	
	public $denied = true;
	
	public $status = 301;
	
	public $redirect = "/";
}