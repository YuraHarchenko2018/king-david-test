<?php

namespace App\Traits;

use Firebase\JWT\JWT;

trait JWTHelperTrait
{
    
    public function jwtEncode($payload)
    {
        $key = $_ENV['JWT_SECRET_KEY'] ?? "";

        if(!empty($key)) {
            $jwt = JWT::encode($payload, $key);
            return $jwt;
        }

        return false;
    }

    public function jwtDecode($jwt)
    {
        $key = $_ENV['JWT_SECRET_KEY'] ?? "";

        try {
            $jwt_decoded = (array) JWT::decode($jwt, $key, array('HS256'));
        } catch(\Exception $e) {
            return false;
        }

        return $jwt_decoded;
    }

}