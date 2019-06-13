<?php

namespace src\classes;

class RouterCollection {

    private static $params = [];

    /**
     * 
     * Function for set routes with GET request method
     * 
     * @param   $uri    string
     * @param   $name   string
     * 
     */
    public static function get($uri, $name)
    {
        self::setParams(self::prepareParams($uri));
        self::setRoute(self::prepare($uri, $name, 'GET'));
    }

    /**
     * 
     * Function for set routes with POST request method
     * 
     * @param   $uri    string
     * @param   $name   string
     * 
     */
    public static function post($uri, $name)
    {
        $data = self::prepare($uri, $name, 'POST');
        self::setRoute($data);
    }

    /**
     * 
     * Function for set routes with PUT request method
     * 
     * @param   $uri    string
     * @param   $name   string
     * 
     */
    public static function put($uri, $name)
    {
        $data = self::prepare($uri, $name, 'PUT');
        self::setRoute($data);
    }

    /**
     * 
     * Function for set routes with DELETE request method
     * 
     * @param   $uri    string
     * @param   $name   string
     * 
     */
    public static function delete($uri, $name)
    {
        $data = self::prepare($uri, $name, 'DELETE');
        self::setRoute($data);
    }

    private static function prepareParams($uri)
    {
        preg_match_all('/\{(.*?)\}/', $uri, $matches);
        return array_map(function ($m) {
            return trim($m, '?');
        }, $matches[1]);
    }

    private static function setParams($params)
    {
        for($i = 0; $i < count($params); $i++) {
            self::$params['param'.$i] = $params[$i];
        }
    }

    /**
     * 
     * Prepare data for insert into $GLOBALS['routes']
     * 
     * @param   $uri    string
     * @param   $name   string
     * @param   $method string
     * 
     * @return  array   array with set uri, controller, action and method
     * 
     */
    private static function prepare($uri, $name, $method)
    {
        
        $name = explode('@', $name);

        return [
            'uri' => $uri,
            'controller' => reset($name),
            'action' => end($name),
            'method' => $method,
            'params' => self::$params
        ];
    }

    /**
     * 
     * Set route in $GLOBALS['routes']
     * 
     * @param   $data   array
     * 
     */
    private static function setRoute($data)
    {
        array_push($GLOBALS['routes'], $data);
        echo '<pre>';
        print_r($GLOBALS['routes']);
    }

}