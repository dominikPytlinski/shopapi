<?php

namespace src\classes;

class RouterCollection {

    function __construct()
    {
        
    }

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
        $data = self::prepare($uri, $name, 'GET');
        self::setRoute($data);
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
        $url = explode('/', $uri);
        $name = explode('@', $name);

        return [
            'uri' => $uri,
            'controller' => reset($name),
            'action' => end($name),
            'method' => $method,
            'params' => count($url) - 2
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