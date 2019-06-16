<?php

namespace src\classes;

use src\classes\Database as DB;

class Model {

    private $values = [];
    private $conditions = [];
    private $whereString = '';
    private $queryType = '';

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
        $values = [];
        $whereConditions = [];

        if(is_array($conditions[0])) {
            foreach($conditions as $cond) {
                $condition = filter_var($cond[0], FILTER_SANITIZE_STRING);
                $operator = filter_var($cond[1], FILTER_SANITIZE_STRING);
                $value = filter_var($cond[2], FILTER_SANITIZE_STRING);

                $whereString .= $condition.' '.$operator. ' :'.$condition.' AND ';
                array_push($values, $value);
                array_push($whereConditions, ':'.$condition);
            }
            $model = new Model();
            $model->prepareForQuery($values, rtrim($whereString, ' AND '), $whereConditions, 'where');
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

    private function prepareForQuery($values, $whereString, $whereConditions, $type)
    {
        $this->values = $values;
        $this->whereString = $whereString;
        $this->type = $type;
        $this->conditions = $whereConditions;
    }

    private function query($sql)
    {
        $sth = DB::connect()->prepare($sql);
    }

    public function get($table)
    {
        switch ($this->type) {
            case 'where':
            $sql = "SELECT * FROM $table WHERE $this->whereString";
            $this->query($sql);
                break;
            
            default:
                # code...
                break;
        }
    }

}