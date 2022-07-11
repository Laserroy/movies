<?php

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo "You must identify yourself";
    exit;
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();