<?php

namespace api\controllers;

use src\classes\Controller;
use src\classes\Auth;
use src\classes\Hash;

class LoginController extends Controller {

    function __construct($token)
    {
        parent::__construct(__CLASS__);
        return (!Auth::token($token)) ? $this->auth = false : $this->auth = true;
    }

    public function index(Object $request, $token)
    {
        print_r($request);
    }

}