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
        if($data != null) {
            if(isset($data->code)) {
                echo $data->message;
            } else {
                $this->loadController($data->controller);
                $this->loadAction($data->action, $data->params);
            }
        } else {
            http_response_code(404);
        }
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
            } else {
                return (object) ['code' => 400, 'message' => 'There is no such a controller'];
            }

            if($route['action'] == end($this->url)) {
                $data['action'] = $route['action'];
            } else {
                return (object) ['code' => 400, 'message' => 'There is no such an action'];
            }

            if($route['params'] == count($this->url) - 2) {
                $data['params'] = $route['params'];
            } else {
                return (object) ['code' => 400, 'message' => 'Invalid number of parmas'];
            }

            if($route['method'] == $_SERVER['REQUEST_METHOD']) {
                $data['method'] = $route['method'];
            } else {
                return (object) ['code' => 405, 'message' => 'Invalid http method'];
            }

            if($route['uri'] == $this->setUri()) {
                $data['uri'] = $route['uri'];
            } else {
                return (object) ['code' => 404, 'message' => 'Invalid enpoint'];
            }
        }

        return (object) $data;
        
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

    private function loadController($controller)
    {
        if(file_exists('api/controllers/'.$controller.'.php')) {
            require 'api/controllers/'.$controller.'.php';
            $class = 'api\controllers\\'.$controller;
            $this->controller = new $class();
        }
    }

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

}