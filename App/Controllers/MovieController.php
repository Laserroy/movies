<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class MovieController
{
    public function index()
    {
        $blade = new Blade('views', 'cache');
        echo $blade->render('index', ['name' => 'John Doe']);
    }
}