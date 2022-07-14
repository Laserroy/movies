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
        $search = $_GET['search'] ?? "";
        $filter = $_GET['filter'] ?? "";
        $searchQuery = "";

        if ($search) {
            $escapedSearch = addslashes($search);
            $searchQuery = $filter === 'star'
                ? "WHERE stars.full_name LIKE '%$escapedSearch%'"
                : "WHERE title LIKE '%$escapedSearch%'";
        }

        $this->db->query("SELECT movies.*, GROUP_CONCAT(stars.full_name SEPARATOR ', ') AS stars
            FROM movies
            LEFT JOIN movie_stars ON movies.id = movie_stars.movie_id
            LEFT JOIN stars ON stars.id = movie_stars.star_id "
            . $searchQuery
            . " GROUP BY movies.id
            ORDER BY movies.title ASC");

        $movies = $this->db->resultset();

        echo $this->template->render('index', compact(['movies', 'search', 'filter']));
    }

    public function show($id)
    {
        $this->db->query("SELECT movies.*, GROUP_CONCAT(stars.full_name SEPARATOR ', ') AS stars
            FROM movies
            LEFT JOIN movie_stars ON movies.id = movie_stars.movie_id
            LEFT JOIN stars ON stars.id = movie_stars.star_id
            WHERE movies.id = :id");
        $this->db->bind(':id', $id, 1);

        $movie = $this->db->single();

        echo $this->template->render('show', compact('movie'));
    }

    public function create()
    {
        echo $this->template->render('create');
    }

    public function store()
    {
        $form = $_POST;

        $this->db->query('SELECT id FROM movies WHERE title = :title');
        $this->db->bind(':title', $form['title']);
        $this->db->execute();

        if ($this->db->rowCount()) {
            echo json_encode(['status' => 422, 'message' => $form['title'] . " already exist"]);
            exit(422);
        }

        $this->db->query('INSERT INTO movies (title, release_year, format) VALUES (?, ?, ?)');
        $this->db->bind(1, $form['title']);
        $this->db->bind(2, $form['release_year']);
        $this->db->bind(3, $form['format']);
        $this->db->execute();

        $newMovieId = $this->db->lastInsertId();

        if (!empty($form['stars'])) {
            foreach ($form['stars'] as $starId) {
                $this->db->query('INSERT INTO movie_stars (movie_id, star_id) VALUES (:movie_id, :star_id)');
                $this->db->bind(':movie_id', $newMovieId);
                $this->db->bind(':star_id', $starId);
                $this->db->execute();
            }
        }

        echo json_encode(['status' => 200, 'message' => $form['title'] . " successfully created"]);
    }

    public function delete($id)
    {
        $this->db->query('DELETE FROM movies WHERE id = :id');
        $this->db->bind(':id', $id, 1);
        $this->db->execute();

        echo json_encode(['status' => 200, 'message' => "successfully deleted"]);
    }
}