<?php

namespace App\Controllers;

use Database\Database;
use Jenssegers\Blade\Blade;

class StarController
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
        $this->db->query('SELECT * FROM stars ORDER BY full_name ASC');
        $stars = $this->db->resultset();

        return $this->template->render('stars.index', compact('stars'));
    }

    public function typeahead($request)
    {
        $queryString = $request->paramsGet();
        $search = $queryString['q'];

        $this->db->query('SELECT * FROM stars WHERE full_name LIKE :search');
        $this->db->bind(':search', "%$search%");
        $stars = $this->db->resultset();

        return json_encode($stars);
    }

    public function store($request)
    {
        $form = $request->paramsPost();
        $form = $request->paramsPost();

        $this->db->query('INSERT IGNORE INTO stars (full_name) VALUES (:full_name)');
        $this->db->bind(':full_name', $form['full_name'], 2);
        $this->db->execute();

        header("Location: /stars");
        exit();
    }

    public function delete($id)
    {
        $this->db->query('DELETE FROM stars WHERE id = :id');
        $this->db->bind(':id', $id, 1);
        $this->db->execute();

        header("Location: /stars");
        exit();
    }
}