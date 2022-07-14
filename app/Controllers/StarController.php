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

        echo $this->template->render('stars.index', compact('stars'));
    }

    public function typeahead()
    {
        $search = $_GET['q'];

        $this->db->query('SELECT * FROM stars WHERE full_name LIKE :search');
        $this->db->bind(':search', "%$search%");
        $stars = $this->db->resultset();

        echo json_encode($stars);
    }

    public function store()
    {
        $form = $_POST;

        $this->db->query('SELECT id FROM stars WHERE full_name = :full_name');
        $this->db->bind(':full_name', $form['full_name']);
        $this->db->execute();

        if ($this->db->rowCount()) {
            echo json_encode(['status' => 422, 'message' => $form['full_name'] . " already exist"]);
            exit(422);
        }

        $this->db->query('INSERT INTO stars (full_name) VALUES (:full_name)');
        $this->db->bind(':full_name', $form['full_name'], 2);
        $this->db->execute();

        echo json_encode(['status' => 200, 'message' => $form['full_name'] . " successfully created"]);
    }

    public function delete($id)
    {
        $this->db->query('DELETE FROM stars WHERE id = :id');
        $this->db->bind(':id', $id, 1);
        $this->db->execute();

        echo json_encode(['status' => 200, 'message' => "successfully deleted"]);
    }
}