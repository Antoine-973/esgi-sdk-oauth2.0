<?php
namespace App;
use App\Provider;
use App\Request;
use App\ProviderInterface;

// 2) You need to copy & paste this file for adding your provider, and just changing the filename & the classname
class ExampleProvider extends Provider implements ProviderInterface
{
    private $code;

    //In the bellow construc if you dont have options and header additional parameters let it to $options = [] & $headers = [] If you have an optional parameters you need to replace it juste by the variable name : $options, $headers
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
        //Here for google who needs specific headers parameters you need to add $this->headersparameters to the getAccessToken() method if you dont have headers specific parameters just use the same line without the $this->headersparameters parameter : $access_token = $this->getAccessToken($code, true);
        $access_token = $this->getAccessToken($code, true);

        $headers = $request->setHeaders('GET', ["Authorization: Bearer ${access_token}", "User-Agent: github"]); // or  $access_token = ($code) ? $this->getAccessToken($code, true, $this->headers) : null; if header parameters

        return $access_token ? $request->get($this->user_info_url, $headers) : false;
    }

    //Here you can edit the login link
    public function getAuthLink()
    {
        $link = $this->getAuthorizationUrl();

        return "<div class='github-link'><a href='$link'>Login with Github.</a></div>";
    }


}
