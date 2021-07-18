<?php
namespace App;
use App\Provider;
use App\ProviderInterface;
use App\Request;

class FacebookProvider extends Provider implements ProviderInterface
{

    public function __construct($client_id, $client_secret, $auth_url,  $access_token_url, $user_info_url, $redirect_uri, $options = [])
    {
        parent::__construct($client_id, $client_secret, $auth_url,  $access_token_url, $user_info_url, $redirect_uri, $options = []);

        $this->client_id = $client_id;
        $this->client_secret = $client_secret;

        $this->auth_url = $auth_url;
        $this->access_token_url = $access_token_url;
        $this->user_info_url = $user_info_url;

        $this->redirect_uri = $redirect_uri;
        $this->options = $options;
    }

    public function getUser($code)
    {
        $request = new Request();
        $access_token = $this->getAccessToken($code, true);

        $headers =   $request->setHeaders('GET', "Authorization: Bearer ${access_token}");

        return $access_token ? $request->get($this->user_info_url, $headers) : false;
    }

    public function getAuthLink()
    {
        $link = $this->getAuthorizationUrl();
        return "<div class='facebook-link'><a href='$link'>Login with Facebook.</a></di>";
    }
}