<?php

namespace api\controllers;

use src\classes\Controller;
use src\classes\Auth;
use src\classes\Hash;
use api\models\User;

class LoginController extends Controller {

    function __construct($token)
    {
        parent::__construct();
        return $this->auth = true;
    }

    public function index($request)
    {
        $user = User::where([
            ['login', '=', $request->login],
            ['password', '=', Hash::create($request->password)]
        ])->get('users'); 
        
        
        echo ($user) ? json_encode(['jwt' => Auth::createToken($user), 'message' => 'Log in successfully']) : json_encode(['message' => 'Incorrect login or password']);
    }

}