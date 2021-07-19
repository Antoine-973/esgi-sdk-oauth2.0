<?php
namespace App;
use App\Provider;
use App\Request;
use App\ProviderInterface;

class GithubProvider extends Provider implements ProviderInterface
{
    private $code;

    public function __construct($client_id, $client_secret, $auth_url,  $access_token_url, $user_info_url, $redirect_uri, $options = [], $headers = [])
    {
        parent::__construct($client_id, $client_secret, $auth_url,  $access_token_url, $user_info_url, $redirect_uri, $options = [], $headers = []);

        $this->client_id = $client_id;
        $this->client_secret = $client_secret;

        $this->auth_url = $auth_url;
        $this->access_token_url = $access_token_url;
        $this->user_info_url = $user_info_url;

        $this->redirect_uri = $redirect_uri;
        $this->options = $options;
        $this->headers = $headers;
    }

    public function getUser($code)
    {
        $request = new Request();
        $access_token = $this->getAccessToken($code, true);

        $headers = $request->setHeaders('GET', ["Authorization: Bearer ${access_token}", "User-Agent: github"]);

        return $access_token ? $request->get($this->user_info_url, $headers) : false;
    }

    public function getAuthLink()
    {
        $link = $this->getAuthorizationUrl();
        $css = "
            display:inline-block;
            border-radius: 4px;
            padding:0.7em 1.7em;
            margin-bottom : 10px;
            text-decoration:none;
            font-weight:400;
            color:#FFFFFF;
            background: #212121;
            text-align:center;
            font-family:sans-serif;
            font-weight:bold;
            ";

        return "<div class='github-link'><a style='$css' href='$link'>Login with Github</a></div><br>";
    }


}
