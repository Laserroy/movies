<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../bootstrap.php';

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {

    $r->addRoute('GET', '/', '\App\Controllers\MovieController@index');
    $r->addRoute('GET', '/{id:\d+}', '\App\Controllers\MovieController@show');
    $r->addRoute('GET', '/create', '\App\Controllers\MovieController@create');
    $r->addRoute('POST', '/', '\App\Controllers\MovieController@store');
    $r->addRoute('DELETE', '/{id:\d+}', '\App\Controllers\MovieController@delete');

    $r->addRoute('GET', '/stars', '\App\Controllers\StarController@index');
    $r->addRoute('GET', '/stars/typeahead', '\App\Controllers\StarController@typeahead');
    $r->addRoute('POST', '/stars', '\App\Controllers\StarController@store');
    $r->addRoute('DELETE', '/stars/{id:\d+}', '\App\Controllers\StarController@delete');

    $r->addRoute('POST', '/import', '\App\Controllers\ImportController@importMovies');

});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (isset($_POST['_method']) && $_POST['_method'] === 'DELETE') {
    $httpMethod = 'DELETE';
}

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        list($class, $method) = explode("@", $handler, 2);
        call_user_func_array(array(new $class, $method), $vars);
        break;
}
