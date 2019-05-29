<?php

/**
 * 
 * Require provider file
 * 
 */
require $GLOBALS['path']['provider'];

foreach($providers as $key => $provider) {
    if($key == 'route') {
        require 'routes/'.$provider.'.php';
    } else {
        require $GLOBALS['path']['src'].'classes/'.$provider.'.php';
    }
}