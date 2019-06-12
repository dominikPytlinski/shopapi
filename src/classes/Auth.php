<?php

namespace src\classes;

use src\classes\Database as DB;
use src\classes\Hash;

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
        $sql = 'SELECT id FROM tokens WHERE token = :token';
        $sth = DB::connect()->prepare($sql);
        $sth->bindValue(':token', $token, \PDO::PARAM_STR);
        $sth->execute();
        $e = $sth->errorInfo();
        if(empty(end($e))) {
            $row = $sth->rowCount();
            return ($row > 0) ? true : false;
        } else {
            echo end($e);
        }
    }

}