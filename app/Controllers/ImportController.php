<?php

namespace App\Controllers;

use Database\Database;

class ImportController
{
    const CHUNK_LENGTH = 4;

    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function importMovies()
    {
        $savedCount = 0;

        try {
            $files = $_FILES;
            $content = file_get_contents($files['movies_import']['tmp_name']);

            $raw = explode("\n", $content);
            $filtered = array_filter($raw, fn($item) => $item !== "");
            $rawMovieChunks = array_chunk($filtered, 4);
            $normalized = $this->normalizeMovies($rawMovieChunks);
            $savedCount = $this->saveMovies($normalized);
        } catch (\Exception $e) {
            echo json_encode(['status' => 500, 'message' => $e->getMessage()]);
            exit();
        }

        echo json_encode(['status' => 200, 'message' => "Saved $savedCount movie(s)"]);
    }

    /**
     * @throws \Exception
     */
    private function normalizeMovies($movieChunks): array
    {
        $normalized = [];

        foreach ($movieChunks as $movieChunk) {
            if (count($movieChunk) != self::CHUNK_LENGTH) {
                throw new \Exception("Invalid format");
            }

            [$rawTitle, $rawYear, $rawFormat, $rawStars] = $movieChunk;
            $temp = [];
            $temp['title'] = $rawTitle ? preg_replace("/Title: /", "", $rawTitle) : "";
            $temp['release_year'] = $rawYear ? preg_replace("/Release Year: /", "", $rawYear) : "";
            $temp['format'] = $rawFormat ? preg_replace("/Format: /", "", $rawFormat) : "";
            $temp['stars'] = $rawStars ? explode(", ", preg_replace("/Stars: /", "", $rawStars)) : "";

            $normalized[] = $temp;
        }

        return $normalized;
    }

    private function saveMovies($movies) :int
    {
        $savedCount = 0;

        foreach ($movies as $movieData) {
            $this->db->query('SELECT * FROM movies WHERE title = :title');
            $this->db->bind(':title', $movieData['title'], 2);
            $this->db->execute();
            $exist = $this->db->rowCount();

            if ($exist) {
                continue;
            }

            $this->db->query('INSERT INTO movies (title, release_year, format) VALUES (?, ?, ?)');
            $this->db->bind(1, $movieData['title']);
            $this->db->bind(2, $movieData['release_year']);
            $this->db->bind(3, $movieData['format']);
            $this->db->execute();

            $newMovieId = $this->db->lastInsertId();
            $savedCount++;

            if (empty($movieData['stars'])) {
                continue;
            }

            foreach ($movieData['stars'] as $fullName) {
                $this->db->query('SELECT * FROM stars WHERE full_name = :full_name');
                $this->db->bind(':full_name', $fullName, 2);
                $this->db->execute();
                $exist = $this->db->rowCount();

                if ($exist) {
                    $this->db->query('SELECT * FROM stars WHERE full_name = :full_name');
                    $this->db->bind(':full_name', $fullName, 2);
                    $starId = $this->db->execute();
                } else {
                    $this->db->query('INSERT INTO stars (full_name) VALUES (:full_name)');
                    $this->db->bind(':full_name', $fullName, 2);
                    $this->db->execute();
                    $starId = $this->db->lastInsertId();
                }

                $this->db->query('INSERT INTO movie_stars (movie_id, star_id) VALUES (:movie_id, :star_id)');
                $this->db->bind(':movie_id', $newMovieId);
                $this->db->bind(':star_id', $starId);
                $this->db->execute();
            }
        }

        return $savedCount;
    }
}