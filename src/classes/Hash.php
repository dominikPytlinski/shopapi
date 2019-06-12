<?php

namespace src\classes;

class Hash {

    public static function create($data)
    {
        $context = hash_init('sha1', HASH_HMAC, getenv('APP_KEY'));
        hash_update($context, $data);
        return hash_final($context);
    }

}