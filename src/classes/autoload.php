<?php

namespace src\classes;

/**
 * 
 * Require classes
 * 
 */
spl_autoload_register(function($name) {
    require $name.'.php';
});