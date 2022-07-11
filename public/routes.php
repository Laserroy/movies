<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../bootstrap.php';

$router = new \Klein\Klein();

$router->respond('GET', '/?', function ($request) {
    echo (new \App\Controllers\MovieController)->index($request);
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

$router->respond('GET', '/stars', function ($request) {
    echo (new \App\Controllers\StarController())->index($request);
});

$router->respond('GET', '/stars/typeahead', function ($request) {
    echo (new \App\Controllers\StarController())->typeahead($request);
});

$router->respond('POST', '/stars', function ($request) {
    echo (new \App\Controllers\StarController())->store($request);
});

$router->respond('DELETE', '/stars/[i:id]', function ($request) {
    echo (new \App\Controllers\StarController())->delete($request->id);
});

$router->respond('POST', '/import', function ($request) {
    echo (new \App\Controllers\ImportController())->importMovies($request);
});

$router->dispatch();

