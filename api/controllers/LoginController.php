<?php

namespace api\controllers;

use src\classes\Controller;
use src\classes\Auth;
use src\classes\Hash;
use api\models\User;

class LoginController extends Controller {

    function __construct($token)
    {
        parent::__construct(__CLASS__);
        return (!Auth::token($token)) ? $this->auth = false : $this->auth = true;
    }

    public function index($token)
    {
        User::where([
            ['login', '=', 'admin'],
            ['paswword', '=', 'admin']
        ])->get('users');
    }

}