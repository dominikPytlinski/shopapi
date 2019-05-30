<?php

namespace src\classes;

use src\classes\Database as DB;

class Model {

    public static function find()
    {
        echo 'find function';
        DB::connect();
    }

}