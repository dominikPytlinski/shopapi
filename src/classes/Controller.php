<?php

namespace src\classes;

class Controller {

    /**
     * 
     * Set connection to the model
     * 
     * @param   $class  string  class name
     * 
     */
    function __construct($class)
    {
        require_once('Auth.php');
        
        $classes = explode('\\', $class);
        $model = substr(end($classes), 0, -10);
        if(file_exists('api/models/'.$model.'.php')) {
            require 'api/models/'.$model.'.php';
        }
    }



}