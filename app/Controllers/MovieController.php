<?php

namespace App\Controllers;

use database\Database;
use Jenssegers\Blade\Blade;

class MovieController
{
    private $template;

    public function __constructor()
    {
        $this->template = new Blade('views', 'cache');
    }

    public function index()
    {
        $db = new Database();
        $db->query('select * from movies');

        $movies = $db->resultset();

        $blade = new Blade('views', 'cache');
        return $blade->render('index', ['movies' => $movies]);
    }

    public function show($id)
    {
        return $id;
    }

    public function create()
    {
        $blade = new Blade('views', 'cache');
        return $blade->render('create');
    }

    public function store($request)
    {
        $form = $request->paramsPost();

        $db = new Database();

        $db->query('insert into movies (title, release_year, format) values (?, ?, ?)');
        $db->bind(1, $form['title']);
        $db->bind(2, $form['release_year']);
        $db->bind(3, $form['format']);
        $db->execute();

        header("Location: /");
        exit();
    }

    public function delete($id)
    {
        $db = new Database();

        $db->query('delete from movies where id = :id');
        $db->bind('id', $id, 1);
        $db->execute();

        header("Location: /");
        exit();
    }
}