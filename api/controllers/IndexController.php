<?php

namespace api\controllers;

use src\classes\Controller;
use src\classes\Auth;
use api\models\Index;

class IndexController extends Controller {

    function __construct($token)
    {
        parent::__construct(__CLASS__);
        (!Auth::token($token)) ? http_response_code(401) : true;
    }

    public function index($params)
    {
        $token = reset($params); 
    }
}