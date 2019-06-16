<?php

namespace src\classes;

use src\classes\Database as DB;

class Model {

    private $conditionsAndValues = [];
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
            $model = new Model();
            $model->prepareForQuery($conditionAndValues, rtrim($whereString, ' AND '), 'where');
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

    private function prepareForQuery($conditionAndValues, $whereString, $type)
    {
        $this->whereString = $whereString;
        $this->type = $type;
        $this->conditionsAndValues = $conditionAndValues;
    }

    private function query($sql)
    {
        $sth = DB::connect()->prepare($sql);
        print_r($this->conditionsAndValues);
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