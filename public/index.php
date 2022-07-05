<?php

require __DIR__ . '/../vendor/autoload.php';

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

match([$method, $uri]) {
    ['GET', '/'] => test(),
};

function test()
{
    echo "test";
}
