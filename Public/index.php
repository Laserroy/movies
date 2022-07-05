<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../bootstrap.php';

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

match([$method, $uri]) {
    ['GET', '/'] => (new \App\Controllers\MovieController)->index(),
};

