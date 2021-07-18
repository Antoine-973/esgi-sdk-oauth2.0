<?php
namespace App;

interface ProviderInterface{

    public function getUser($code);

    public function getAuthLink();
}