<?php
namespace App;

class Response {

    public function getBody(array $data, $http_code)
    {
        http_response_code($http_code);
        return json_encode($data);
    }
}