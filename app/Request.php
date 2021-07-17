<?php
namespace App;

class Request{

    function get(string $url, $ctx = null)
    {
        $data = file_get_contents($url, false, $ctx);
        return ($data) ? json_decode($data, true) : [];
    }

    public function setHeaders(string $method, $headers)
    {
        return stream_context_create([
            'http' => [
                'method' => $method,
                'header' => $headers
            ]
        ]);
    }

    public function getUrl(string $url, array $params = [])
    {
        return $url . (!empty($params) ? '?' . http_build_query($params) : '\n');
    }
}