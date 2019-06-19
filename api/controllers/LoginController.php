<?php

namespace api\controllers;

use src\classes\Controller;
use src\classes\Auth;
use src\classes\Hash;
use api\models\User;
use Firebase\JWT\JWT;

class LoginController extends Controller {

    function __construct($token)
    {
        parent::__construct(__CLASS__);
        return $this->auth = true;
    }

    public function index()
    {
        // User::where([
        //     ['login', '=', 'admin'],
        //     ['paswword', '=', 'admin']
        // ])->get('users');
        // $key = "example_key";
        // $token = array(
        //     "iss" => "http://example.org",
        //     "aud" => "http://example.com",
        //     "iat" => 1356999524,
        //     "nbf" => 1357000000
        // );

        // $jwt = JWT::encode($token, $key);
        // $decoded = JWT::decode($jwt, $key, array('HS256'));
        
        
    }

}