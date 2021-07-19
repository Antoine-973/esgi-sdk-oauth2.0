<?php

/*
 * 1) For adding a provider first you need to add it's configuration in the /app/config.php file
 *
 * 2) After that you need to copy one of the already existing Provider class and just changing the filename and the classname
 */

require __DIR__ . DIRECTORY_SEPARATOR . 'autoloader.php';

$configs = require('config.php');

$collection = new App\ProviderCollection($configs);

$github = $collection->getProvider('github');
$google = $collection->getProvider('google');
$facebook = $collection->getProvider('facebook');
$app = $collection->getProvider('app');

/*
 * 3) Finaly in this file you need to add a same line as above and edit the providerName called
 *
 * $exampleProvider = $collection->getProvider('example');
 */


// Here the call to the Provider->getAuthLink for displaying your link "Login with ..."
echo $google->getAuthLink();
echo $facebook->getAuthLink();
echo $github->getAuthLink();
echo $app->getAuthLink();

/*
 *  4) For adding the Login with link of your provider you need to add a same line as above and use the variable previously created and calling it's getAuthLink() method.
 *
 * echo $app->getAuthLink();
 */

["code" => $code] = $_GET;

// IF CODE IS NOT NULL -> call getUsers method of the provider and var_dump the user information given by the Oauth provider.
if ($code){

    //Dont display warning error of other method
    \error_reporting(0);

    if ($user = $google->getUser($code)){
        var_dump($user);
        die;
    }

    if ($user = $facebook->getUser($code)){
        var_dump($user);
        die;
    }

    if ($user = $github->getUser($code)){
        var_dump($user);
        die;
    }

    if ($user = $app->getUser($code)){
        var_dump($user);
        die;
    }

    /* 5)
    *
    * Last Step add the following if condition for displaying the user result when receiving the provider response
    *
    *    if ($user = $app->getTest($code)){
    *        var_dump($user);
    *        die;
    *    }
    */

}
