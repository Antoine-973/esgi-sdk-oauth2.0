<?php
namespace App;

class Request{

    function get($url, $ctx = null)
    {
        $data = file_get_contents($url, true, $ctx);
        return ($data) ? json_decode($data, true) : [];
    }

    public function setHeaders( $method, $headers)
    {
        return stream_context_create([
            'http' => [
                'method' => $method,
                'header' => $headers
            ]
        ]);
    }

    public function getUrl($url,  $params = [])
    {
        return $url . (!empty($params) ? '?' . http_build_query($params) : '');
    }
}