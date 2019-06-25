<?php

use src\classes\RouterCollection as Router;

Router::post('user/login', 'UserController@login');

// Router::post('user/index', 'UserController@index');