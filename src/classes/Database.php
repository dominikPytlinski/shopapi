<?php

namespace src\classes;

class Database {

    private static $db;

    /**
     * 
     * Connect with database
     * 
     * @return  $db PDO object
     * 
     */
    function connect()
    {
        if(self::$db == null) {
            try {
                self::$db = new \PDO('mysql:host='.getenv('DB_HOST').';dbname='.getenv('DB_NAME'), getenv('DB_USER'), getenv('DB_PASS'));
            } catch (\Throwable $th) {
                throw $th;
            }
        }
        return self::$db;
    }

}