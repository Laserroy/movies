<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../bootstrap.php';

$router = new \Klein\Klein();

$router->respond('GET', '/?', function () {
    echo (new \App\Controllers\MovieController)->index();
});

$router->respond('GET', '/[i:id]', function ($request) {
    echo (new \App\Controllers\MovieController)->show($request->id);
});

$router->respond('GET', '/create', function () {
    echo (new \App\Controllers\MovieController)->create();
});

$router->respond('POST', '/?', function ($request) {
    echo (new \App\Controllers\MovieController)->store($request);
});

$router->respond('DELETE', '/[i:id]', function ($request) {
    echo (new \App\Controllers\MovieController)->delete($request->id);
});

$router->dispatch();

