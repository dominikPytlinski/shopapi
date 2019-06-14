<?php

namespace src\classes;

use src\classes\Database as DB;

class Model {

    public static function select($name)
    {
        $sql = 'SELECT * FROM users WHERE login = :name';
        $sth = DB::connect()->prepare($sql);
        $sth->bindValue(':name', $name, \PDO::PARAM_STR);
        $sth->execute();

        return $sth->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function where()
    {
        
    }

}