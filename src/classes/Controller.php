<?php

namespace src\classes;

class Controller {

    public $model;

    /**
     * 
     * Set connection to the model
     * 
     * @param   $class  string  class name
     * 
     */
    function __construct($controller)
    {
        // $model = substr($controller, 0, -10);
        // require_once 'api/models/'.$model.'.php';
        // $class = 'api\models\\'.$model;
        // return $this->model = new $class();
    }

}