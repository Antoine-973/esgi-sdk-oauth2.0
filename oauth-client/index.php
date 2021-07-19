<?php
require __DIR__ . DIRECTORY_SEPARATOR . 'autoloader.php';

$configs = require('config.php');

$collection = new App\ProviderCollection($configs);

$github = $collection->getProvider('github');
$google = $collection->getProvider('google');
$facebook = $collection->getProvider('facebook');
$app = $collection->getProvider('app');

echo $google->getAuthLink();
echo $facebook->getAuthLink();
echo $github->getAuthLink();
echo $app->getAuthLink();

["code" => $code] = $_GET;

if ($code){

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

}
