<?php
$routes->group('admin', ['namespace' => 'Admin\Controllers'], function ($routes) {
    $routes->resource("users", ['controller' => 'User\User']);
    $routes->add('dashboard', 'Dashboard::index');
    // Registration
    $routes->get('register', 'Auth\RegistrationController::register', ['as' => 'register']);
    $routes->post('register', 'Auth\RegistrationController::attemptRegister');
    // Activation
    $routes->get('activate-account', 'Auth\RegistrationController::activateAccount', ['as' => 'activate-account']);
    // Login/out
    $routes->get('login', 'Auth\LoginController::login', ['as' => 'login']);
    $routes->post('login', 'Auth\LoginController::attemptLogin');
    $routes->get('logout', 'Auth\LoginController::logout');
    // Forgotten password and reset
    $routes->get('forgot-password', 'Auth\PasswordController::forgotPassword', ['as' => 'forgot-password']);
    $routes->post('forgot-password', 'Auth\PasswordController::attemptForgotPassword');
    $routes->get('reset-password', 'Auth\PasswordController::resetPassword', ['as' => 'reset-password']);
    $routes->post('reset-password', 'Auth\PasswordController::attemptResetPassword');
    // Account settings
    $routes->get('account', 'Auth\AccountController::account', ['as' => 'account']);
    $routes->post('account', 'Auth\AccountController::updateAccount');
    $routes->post('change-email', 'Auth\AccountController::changeEmail');
    $routes->get('confirm-email', 'Auth\AccountController::confirmNewEmail');
    $routes->post('change-password', 'Auth\AccountController::changePassword');
    $routes->post('delete-account', 'Auth\AccountController::deleteAccount');
});
