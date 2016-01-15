<?php

namespace App\Http\Controllers;

use Disting\Base;

class IndexController extends Base
{
	
	public function home()
	{
		$this->load->view('welcome')->vars(['title' => 'welcome', 'app' => $this]);
	}
}