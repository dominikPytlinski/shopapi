<?php

namespace src\classes;

use src\classes\Database as DB;

class Model {

    public static function all($table)
    {
        $sql = "SELECT * FROM $table";
        $sth = DB::connect()->prepare($sql);
        $sth->execute();

        return $sth->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function where($able, $condition)
    {
        
    }

}