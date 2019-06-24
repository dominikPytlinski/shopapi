<?php

use src\classes\RouterCollection as Router;

Router::post('user/login', 'UserController@login');