<?php

namespace src\classes;

use api\controllers;

class Router {

    private $url;
    private $data = [];
    private $controller;

    function __construct()
    {
        $this->getUrl();
        $data = ($this->url != null) ? $this->prepareRoute() : null;
        (isset($data->code)) ? $this->error(400) : $this->route($data);
    }

    /**
     * 
     * get uri from $_GET['url]
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
            if(substr($route['controller'], 0, -10) == ucfirst(reset($this->url))) {
                $data['controller'] = $route['controller'];
            }

            if($route['action'] == end($this->url)) {
                $data['action'] = $route['action'];
            }

            if($route['params'] == count($this->url) - 2) {
                $data['params'] = $route['params'];
            }

            if($route['method'] == $_SERVER['REQUEST_METHOD']) {
                $data['method'] = $route['method'];
            }

            if($route['uri'] == $this->setUri()) {
                $data['uri'] = $route['uri'];
            }
        }

        if(!isset($data['controller']) || !isset($data['action']) || !isset($data['params']) || !isset($data['method']) || !isset($data['uri'])) {
            return (object) ['code' => 400];
        } else {
            return (object) $data;
        }
        
    }

    /**
     * 
     * set uri using $this->url
     * 
     * @return  $uri    string
     * 
     */
    private function setUri()
    {
        $params = '';

        for($i = 1; $i < sizeof($this->url) - 1; $i++) {
            $params .= '{'.reset($this->url).'}/';
        }

        return $uri = reset($this->url).'/'.$params.end($this->url);
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
        $methodParams = [];

        for ($i = 1; $i <= count($this->url) - 2; $i++) {
            array_push($methodParams, $this->url[$i]);
        }

        if(count($methodParams) == $params) {
            $this->controller->$action((object) $methodParams);
        }
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
        if($data != null) {
            if(isset($data->code)) {
                $this->error($data->code);
            } else {
                (!$this->loadController($data->controller)) ? $this->error(401) : $this->loadAction($data->action, $data->params);
            }
        } else {
            $this->error(404);
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