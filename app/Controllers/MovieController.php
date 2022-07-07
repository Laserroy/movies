<?php

namespace App\Controllers;

use Database\Database;
use Jenssegers\Blade\Blade;

class MovieController
{
    private Database $db;
    private Blade $template;

    public function __construct()
    {
        $this->db = new Database();
        $this->template = new Blade('views', 'cache');
    }

    public function index()
    {
        $this->db->query('select * from movies order by title asc');

        $movies = $this->db->resultset();

        return $this->template->render('index', compact('movies'));
    }

    public function show($id)
    {
        $this->db->query("SELECT movies.*, GROUP_CONCAT(stars.full_name SEPARATOR ', ') AS stars
            FROM movies
            INNER JOIN movie_stars ON movies.id = movie_stars.movie_id
            INNER JOIN stars ON stars.id = movie_stars.star_id
            WHERE movies.id = :id");
        $this->db->bind(':id', $id, 1);

        $movie = $this->db->single();

        return $this->template->render('show', compact('movie'));
    }

    public function create()
    {
        return $this->template->render('create');
    }

    public function store($request)
    {
        $form = $request->paramsPost();

        $this->db->query('insert into movies (title, release_year, format) values (?, ?, ?)');
        $this->db->bind(1, $form['title']);
        $this->db->bind(2, $form['release_year']);
        $this->db->bind(3, $form['format']);
        $this->db->execute();

        $newMovieId = $this->db->lastInsertId();

        if (!empty($form['stars'])) {
            foreach ($form['stars'] as $starId) {
                $this->db->query('insert into movie_stars (movie_id, star_id) values (:movie_id, :star_id)');
                $this->db->bind(':movie_id', $newMovieId);
                $this->db->bind(':star_id', $starId);
                $this->db->execute();
            }
        }

        header("Location: /");
        exit();
    }

    public function delete($id)
    {
        $this->db->query('delete from movies where id = :id');
        $this->db->bind(':id', $id, 1);
        $this->db->execute();

        header("Location: /");
        exit();
    }
}