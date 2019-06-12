<?php

namespace routes;

use src\classes\RouterCollection as Router;

Router::get('index/{index}/index', 'IndexController@index');

Router::get('login/{login}/index', 'LoginController@index');