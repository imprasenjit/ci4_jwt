<?php

namespace App\Controllers;

use App\Models\HomeModel;
use App\Models\TradeModel;
use App\Models\MarketModel;
use App\Models\ExchangeModel;
use App\Models\FavoriteModel;
use App\Libraries\Email;

class Home extends Basecontroller
{
	public function __construct()
	{
		helper('form');
	}
	public function sendmail()
	{
		$email=new Email();
		$email->send("prasn2009@gmail.com","My Test","Wow");
	}
	public function index()
	{
	  return view("Admin/index");	
	}
}
