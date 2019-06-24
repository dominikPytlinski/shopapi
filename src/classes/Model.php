<?php

namespace src\classes;

use src\classes\Database as DB;
use src\helpers\ModelHelper;

class Model {

    private static $model;
    private $table;
    private $conditionsAndValues = [];
    private $whereString;
    private $queryType = '';

    function __construct($class)
    {
        self::$model = $class;

        $class = explode('\\', $class);
        $this->table = lcfirst(end($class)).'s';
    }

    public static function all($table)
    {
        $sql = "SELECT * FROM $table";
        $sth = DB::connect()->prepare($sql);
        $sth->execute();

        return $sth->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function where($conditions)
    {
        $whereString = '';
        $conditionAndValues = [];

        if(is_array($conditions[0])) {
            foreach($conditions as $cond) {
                $condition = filter_var($cond[0], FILTER_SANITIZE_STRING);
                $operator = filter_var($cond[1], FILTER_SANITIZE_STRING);
                $value = filter_var($cond[2], FILTER_SANITIZE_STRING);

                $whereString .= $condition.' '.$operator. ' :'.$condition.' AND ';
                $cav = [':'.$condition, $value];
                array_push($conditionAndValues, $cav);
            }
            $model = new self::$model;
            $model->prepareWhereQuery($conditionAndValues, rtrim($whereString, ' AND '));
            return $model;
        }
    }

    public static function token($token)
    {
        $sql = 'SELECT * FROM tokens WHERE token = :token';
        $sth = DB::connect()->prepare($sql);
        $sth->bindValue(':token', $token, \PDO::PARAM_STR);
        $sth->execute();
        $e = $sth->errorInfo();
        if(empty(end($e))) {
            $row = $sth->rowCount();
            return ($row > 0) ? true : false;
        } else {
            echo end($e);
            exit();
        }
    }

    private function prepareWhereQuery($conditionAndValues, $whereString)
    {
        $this->whereString = 'WHERE '.$whereString;
        $this->conditionsAndValues = $conditionAndValues;
    }

    public function get()
    {
        
    }

    public function belongsTo($class)
    {
        $table = $class.'s';
        $foreignKey = $class.'_id';

        if(isset($this->whereString)) {
            $sql = "SELECT $this->table.*, $table.$class FROM $this->table LEFT JOIN $table ON $this->table.$foreignKey = $table.id $this->whereString";
            $sth = DB::connect()->prepare($sql);
            foreach($this->conditionsAndValues as $cav) {
                $sth->bindValue($cav[0], $cav[1], \PDO::PARAM_STR);
            }
            $sth->execute();
            $e = $sth->errorInfo();
            if(empty(end($e))) {
                return $sth->fetchAll(\PDO::FETCH_OBJ);
            } else {
                return end($e);
            }
        }
    }

}