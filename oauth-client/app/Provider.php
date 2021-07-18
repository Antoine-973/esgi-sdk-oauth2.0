<?php
namespace App;
use App\Request;

abstract class Provider
{
    protected  $client_id;
    protected  $client_secret;
    protected  $auth_url;
    protected  $user_info_url;
    protected  $access_token_url;
    protected  $redirect_uri;
    protected  $options;
    protected  $headers;
    protected $request;

    protected function __construct($client_id, $client_secret, $auth_url,  $access_token_url, $user_info_url, $redirect_uri, $options = [], $headers = [])
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;

        $this->auth_url =  $auth_url;
        $this->access_token_url = $access_token_url;
        $this->user_info_url = $user_info_url;

        $this->redirect_uri = $redirect_uri;

        $this->options = $options;

        $this->request = new Request();
    }

    protected function getAccessToken(string $code, bool $is_post = false, $options = [])
    {
        $context = $is_post ? $this->request->setHeaders('POST', ($options) ? $options : ['Content-Type: application/x-www-form-urlencoded', 'Content-Length: 0', 'Accept: application/json']) : null;
        $url = $this->request->getUrl($this->access_token_url, [
            'code' => $code,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirect_uri,
            'grant_type' => 'authorization_code',
        ]);

        return $this->request->get($url, $context)['access_token'];
    }


    public function getAuthorizationUrl()
    {
 
        return $this->request->getUrl($this->auth_url, array_merge([
            'response_type' => 'code',
            'redirect_uri' => $this->redirect_uri,
            'client_id' => $this->client_id,
        ], $this->options));
    }
}
