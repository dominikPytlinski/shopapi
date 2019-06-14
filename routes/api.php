<?php

namespace routes;

use src\classes\RouterCollection as Router;

Router::post('login/{token}/index', 'LoginController@index');