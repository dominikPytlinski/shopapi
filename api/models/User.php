<?php

namespace api\models;

use src\classes\Model;

class User extends Model {

    function __construct()
    {
        parent::__construct(__CLASS__);
    }

    public function role()
    {
        return $this->belongsTo('role');
    }
}