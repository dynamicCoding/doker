<?php

namespace Disting;

use Disting\LoadClassTrait;
use stdClass;

abstract class Base extends stdClass
{
	use LoadClassTrait;
	
	public function __construct()
	{
		$this->loadView();
		$this->http();
		$this->auth();
		$this->executeService();
		$this->validator();
		$this->db();
		$this->hashSecurity();
	}
	
	protected function loadView()
	{
		$this->register('load', '\Disting\View\View');
	}
	
	protected function http()
	{
		$this->register('request', '\Disting\Http\Request');
	}
	
	protected function executeService()
	{
		$this->register('service', '\Disting\Providers\ServiceProvider');
	}
	
	protected function auth()
	{
		$this->register('auth', '\Disting\Session\Session');
		$this->register('flash', '\Disting\Session\Flash');
		$this->register('security', '\Disting\Session\Token');
	}
	
	protected function hashSecurity()
	{
		$this->register('password', '\Disting\Security\Crypt\Password');
		$this->register('verify', '\Disting\Providers\User\PasswordProvider');
	}
	
	protected function validator()
	{
		$this->register('input', '\Disting\Validator\Validator');
	}
	
	protected function db()
	{
		$this->register('db', '\Ilum\Ilum');
	}
}