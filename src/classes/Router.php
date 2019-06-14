<?php

namespace src\classes;

use api\controllers;

class Router {

    private $url;
    private $parameters = [];
    private $data = [];
    private $controller;

    function __construct()
    {
        $this->getUrl();
        $data = ($this->url != null) ? $this->prepareRoute() : null;
        (isset($data->code)) ? $this->error($data->code) : $this->route($data);
    }

    /**
     * 
     * get uri from $_REQUEST['url]
     * 
     */
    private function getUrl()
    {
        $this->url = (!empty($_REQUEST)) ? explode('/', $_REQUEST['url']) : null;
    }

    /**
     * 
     * prepare array for route
     * 
     * @return  object   params for route or code and message of mistake
     * 
     */
    private function prepareRoute()
    {
        $data = [];

        foreach($GLOBALS['routes'] as $route) {
            if(count(explode('/', $route['uri'])) === count($this->url)) {
                $data['uri'] = $route['uri'];
            }
            if(substr($route['controller'], 0, -10) === ucfirst(reset($this->url))) {
                $data['controller'] = $route['controller'];
            }
            if($route['action'] === end($this->url)) {
                $data['action'] = $route['action'];
            }
            if(count($route['params']) === count($this->url) - 2) {
                $data['params'] = $route['params'];
            }
            if($route['method'] === $_SERVER['REQUEST_METHOD']) {
                $data['method'] = $route['method'];
            }
        }

        if(!isset($data['uri']) || !isset($data['controller']) || !isset($data['action']) || !isset($data['params'])) {
            return (object) ['code' => 400];
        }
        if(!isset($data['method'])) {
            return (object) ['code' => 405];
        }

        return (object) $data;
        
    }

    /**
     * 
     * Create new instace of controller class
     * 
     * @param   $controller         string
     * 
     * @return  $this->controller   object
     * @return                      bool    Flag, if token is or not auth
     * 
     */
    private function loadController($controller)
    {
        if(file_exists('api/controllers/'.$controller.'.php')) {
            require 'api/controllers/'.$controller.'.php';
            $class = 'api\controllers\\'.$controller;
            $this->controller = new $class($this->url[1]);
            return (!$this->controller->auth) ? false : true;
        }
    }

    /**
     * 
     * Call controller action
     * 
     * @param   $action string
     * @param   $params int
     * 
     */
    private function loadAction($action, $params)
    {       
        for ($i = 1; $i <= count($this->url) - 2; $i++) {
            array_push($this->parameters, $this->url[$i]);
        }

        $this->controller->$action((object) $_REQUEST, ...array_values($this->parameters));
    }

    /**
     * 
     * Set controller and action
     * 
     * @param   $data   object
     * 
     */
    private function route($data)
    {
        if($data === null) {
            echo 'pusty url';
            // $this->error(404);
        } else {
            (!$this->loadController($data->controller)) ? $this->error(401) : $this->loadAction($data->action, $data->params);
        }
    }

    /**
     * 
     * Set http response code when error ocured
     * 
     * @param   $code   int
     * 
     */
    private function error($code)
    {
        http_response_code($code);
        exit();
    }

}