<?php

namespace Admin\Controllers;

use App\Controllers\Basecontroller;

class Dashboard extends Basecontroller
{
	public function __construct()
	{
		// helper('form');
	}
	public function index()
	{
	  return view("Admin\Views\Admin\index");	
	}
}
