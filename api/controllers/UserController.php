<?php

namespace api\controllers;

use src\classes\Controller;
use src\classes\Auth;
use src\classes\Hash;
use api\models\User;

class UserController extends Controller {

    function __construct($controller)
    {
        parent::__construct($controller);
        return $this->auth = true;
    }

    public function login($request)
    {
        $user = User::where([
            ['login', '=', $request->login],
            ['password', '=', Hash::create($request->password)]
        ])->role(); 
        
        echo ($user) ? json_encode(['jwt' => Auth::createToken($user), 'message' => 'Logged in successfully']) : json_encode(['message' => 'Incorrect login or password']);
    }

}