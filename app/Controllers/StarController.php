<?php

namespace App\Controllers;

use Database\Database;

class StarController
{
    public function typeahead($request)
    {
        $queryString = $request->paramsGet();
        $search = $queryString['q'];

        $db = new Database();
        $db->query('select * from stars where full_name like :search');
        $db->bind(':search', "%$search%");

        $stars = $db->resultset();

        return json_encode($stars);
    }

    public function store($search)
    {
        $db = new Database();
        $db->query('select * from stars');

        $movies = $db->resultset();
    }
}