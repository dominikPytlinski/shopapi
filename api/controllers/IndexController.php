<?php

namespace api\controllers;

use src\classes\Controller;
use api\models\Index;

class IndexController extends Controller {

    function __construct()
    {
        parent::__construct(__CLASS__);
    }

    public function index($params)
    {
        $token = reset($params);
        Index::auth($token);
    }
}