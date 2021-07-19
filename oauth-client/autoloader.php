<?php
spl_autoload_register(function ($class) {
    $parts = explode('\\', $class);

    include __DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . end($parts) . '.php';
});