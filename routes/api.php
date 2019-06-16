<?php

use src\classes\RouterCollection as Router;

Router::get('login/{token}/index', 'LoginController@index');