<?php

/**
 * 
 * Set provider array for autoload.php file
 * 
 * @return  array   $providers  array of files to require
 * 
 */
return $providers = [
    'router_collection' => 'RouterCollection',
    'route'             => 'api',
    'router'            => 'Router',
    'db'                => 'Database',
    'model'             => 'Model',
    'controller'        => 'Controller'
];