<?php

namespace src\classes;

use src\classes\Database as DB;

class Model {

    /**
     * 
     * Table for SQL request
     * 
     * @var string
     * 
     */
    private $table;

    /**
     * 
     * Table of conditions and values for where statement
     * 
     * @var array
     * 
     */
    private $conditionsAndValues = [];

    /**
     * 
     * The where string for SQL request
     * 
     * @var string
     * 
     */
    private $whereString;

    /**
     * 
     * The order by string for SQL request
     * 
     * @var string
     * 
     */
    private $orderBy;

    /**
     * 
     * Select all records 
     * 
     */
    public function all()
    {
        $class = get_called_class();
        $model = new $class();
        $model->setTable();
        $model->get();
    }

    /**
     * 
     * Create where contidions and values
     * 
     * @param   array   $conditions array of conditions and values
     * @return  object  $model      new model object
     * 
     */
    public function where($conditions)
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
            $class = get_called_class();
            $model = new $class();
            $model->setTable();
            $model->prepareWhereQuery($conditionAndValues, rtrim($whereString, ' AND '));
            return $model;
        }
    }

    /**
     * 
     * Create string for order by
     * 
     * @param   string  $order      order by
     * @param   string  $direction  ASC or DESC
     * @return  object  $model      new model object
     * 
     */
    public function orderBy($order, $direction)
    {
        $class = get_called_class();
        $model = new $class();
        $model->setTable();
        $model->prepareOrderByQuery($order, $direction);
        return $model;
    }

    /**
     * 
     * Select from database
     * 
     * @return  object  $sth    PDO object
     * 
     */
    public function get()
    {
        $sql = "SELECT $this->table.* FROM $this->table $this->whereString $this->orderBy";
        $sth = DB::connect()->prepare($sql);
        if(!empty($this->conditionsAndValues)) {
            foreach($this->conditionsAndValues as $cav) {
                $sth->bindValue($cav[0], $cav[1], \PDO::PARAM_STR);
            }
        }
        $sth->execute();
        $e = $sth->errorInfo();
        if(empty(end($e))) {
            return $sth->fetchAll(\PDO::FETCH_OBJ);
        } else {
            return end($e);
        }
    }

    /**
     * 
     * Select from database whene there is a relationship
     * 
     * @param   string  $class  name of second class
     * @return  object  $sth    PDO object
     * 
     */
    public function belongsTo($class)
    {
        $table = $class.'s';
        $foreignKey = $class.'_id';

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

    /**
     * 
     * Asign where string to $this->whereString and conditions and values to $this->conditionsAndValues
     * 
     * @param   array   $conditionsAndValues    Conditions and values
     * @param   string  $whereString            Where string
     * 
     */
    protected function prepareWhereQuery($conditionAndValues, $whereString)
    {
        $this->whereString = 'WHERE '.$whereString;
        $this->conditionsAndValues = $conditionAndValues;
    }

    /**
     * 
     * Asign order and direction to $this->orderBy
     * 
     * @param   string  $order  order
     * 
     */
    protected function prepareOrderByQuery($order, $direction)
    {
        $this->orderBy = "ORDER BY $order $direction";
    }

    /**
     * 
     * Asign table name to $this->table
     * 
     */
    protected function setTable()
    {
        $class = explode('\\', get_called_class());
        $this->table = lcfirst(end($class)).'s';
    }
}