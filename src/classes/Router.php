<?php

namespace src\classes;

class Router {

    private $url;
    private $data = [];

    function __construct()
    {
        $this->getUrl();
        $this->prepareRoute();
    }

    /**
     * 
     * get uri from $_GET['url]
     * 
     */
    private function getUrl()
    {
        $this->url = explode('/', $_GET['url']);
    }

    /**
     * 
     * prepare array for route
     * 
     * @return  array   params for route
     * 
     */
    private function prepareRoute()
    {
        foreach($GLOBALS['routes'] as $route) {
            if(
            substr($route['controller'], 0, -10) == ucfirst(reset($this->url)) &&
            $route['action'] == end($this->url) &&
            $route['params'] == count($this->url) - 2 &&
            $route['method'] == $_SERVER['REQUEST_METHOD'] &&
            $route['uri'] == $this->setUri()) {
                return [
                    'controller' => $route['controller'],
                    'action' => $route['action'],
                    'method' => $route['method'],
                    'params' => $route['params'],
                    'uri' => $route['uri']
                ];
            } else {
                return false;
            }
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

}