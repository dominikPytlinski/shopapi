<?php

namespace src\classes;

use src\classes\Database as DB;
use src\classes\Hash;
use api\models\User;

class Auth {

    /**
     * 
     * Check if given token is in the database
     * 
     * @param   $token  string
     * 
     * @return  bool
     * 
     */
    public static function token($token)
    {
        $token = filter_var($token, FILTER_SANITIZE_STRING);
        $token = Hash::create($token);
        return User::token($token);
    }

}