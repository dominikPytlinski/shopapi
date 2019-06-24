<?php

namespace api\models;

use src\classes\Model;

class User extends Model {

    function __construct()
    {
        
    }

    public function role()
    {
        return $this->belongsTo('role');
    }
}