<?php

namespace src\classes;

class RouterCollection {

    function __construct()
    {
        
    }

    public static function get($uri, $name)
    {
        $data = self::prepare($uri, $name, 'GET');
        self::setRoute($data);
    }

    public static function post($uri, $name)
    {
        $data = self::prepare($uri, $name, 'POST');
        self::setRoute($data);
    }

    public static function put($uri, $name)
    {
        $data = self::prepare($uri, $name, 'PUT');
        self::setRoute($data);
    }

    public static function delete($uri, $name)
    {
        $data = self::prepare($uri, $name, 'DELETE');
        self::setRoute($data);
    }

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

    private static function setRoute($data)
    {
        array_push($GLOBALS['routes'], $data);
    }

}