<?php

namespace api\models;

use src\classes\Model;

class User extends Model {

    function __construct()
    {
        parent::__construct();
    }

    public function test()
    {
        $model = new Model;
        $model->hasOne('role');
    }
}