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
        self::setParams(self::prepareParams($uri));
        self::setRoute(self::prepare($uri, $name, 'POST'));
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
        self::setParams(self::prepareParams($uri));
        self::setRoute(self::prepare($uri, $name, 'PUT'));
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
        self::setParams(self::prepareParams($uri));
        self::setRoute(self::prepare($uri, $name, 'DELETE'));
    }

    /**
     * 
     * Set parameters names in matches array
     * 
     * @param   $uri        string
     * 
     * @return  $matches    array   parameters names
     * 
     */
    private static function prepareParams($uri)
    {
        preg_match_all('/\{(.*?)\}/', $uri, $matches);
        return array_map(function ($m) {
            return trim($m, '?');
        }, $matches[1]);
    }

    /**
     * 
     * Check if there are double names in the $params array,
     * asign $params to self::$params
     * 
     * @param   $params         array
     * 
     * @return  self::$params   array Array of parameters
     * 
     */
    private static function setParams($params)
    {
        $countParams = array_count_values($params);
        if(count($params) > count($countParams)) {
            echo 'Nie mogą wystąpić dwa parametry o tej samej nazwie';
            exit();
        }
        self::$params = $params;
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
    }

}