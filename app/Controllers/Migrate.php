<?php
namespace App\Controllers;


class Migrate extends Basecontroller
{
        public function index()
        {
                $migrate = \Config\Services::migrations();
                try
                {
                        $migrate->latest();
                }
                catch (\Throwable $e)
                {
                        print_r($e);
                }
        }
}