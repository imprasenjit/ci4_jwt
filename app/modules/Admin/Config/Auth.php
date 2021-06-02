<?php
namespace Admin\Config;

use CodeIgniter\Config\BaseConfig;

class Auth extends BaseConfig
{
	//--------------------------------------------------------------------
    // Views used by Auth Controllers
    //--------------------------------------------------------------------

    public $views = [
        'login' => 'Admin\Views\Auth\login',
        'register' => 'Admin\Views\Auth\register',
        'forgot-password' => 'Admin\Views\Auth\forgot',
        'reset-password' => 'Admin\Views\Auth\reset',
        'account' => 'Admin\Views\Auth\Auth\account'
    ];

    // Layout for the views to extend
    public $viewLayout = 'Admin\Views\Auth\layout';
}
