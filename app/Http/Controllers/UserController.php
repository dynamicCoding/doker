<?php

namespace App\Http\Controllers;

use Disting\Base;
use Disting\Http\Request;

class UserController extends Base
{
	
	public function login(Request $request)
	{
		$this->service->provider('App\Denied\LoginDenied');
		
		$this->load->view('Forms.login')->vars(['title' => 'login', 'app' => $this]);
	}
	
	public function signin()
	{
		$this->service->provider('App\Denied\LoginDenied');
		
		$this->load
			->view('Forms.register')
			->vars(['title' => 'registrarse', 'app' => $this]);
	}
	
	public function signout()
	{
		$this->auth->remove('email');
		$this->auth->remove('id');
		$this->request->status(301);
		$this->request->redirect();
	}
}