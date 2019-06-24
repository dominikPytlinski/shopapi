<?php

namespace src\classes;

use src\classes\Database as DB;
use src\classes\Hash;
use api\models\User;
use Firebase\JWT\JWT;

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

    public function createToken($data)
    {
        $key = getenv('API_KEY');
        $token = array(
            "iss"  => getenv('API_URL'),
            "aud"  => getenv('API_URL'),
            "iat"  => time(),
            "exp"  => time() + 1200,
            "nbf"  => 1357000000,
            "id"   => $data[0]->id,
            "name" => $data[0]->name,
            "role" => $data[0]->role
        );

        return $jwt = JWT::encode($token, $key);
    }

}