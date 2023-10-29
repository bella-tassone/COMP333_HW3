<?php
/**
 * RatingModel class handles rating-related database operations
 * 
 */

require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class RatingModel extends Database
{
    public function getRating($params)
    {
        return $this->read("SELECT * FROM ratings WHERE username = ? AND song = ? AND artist = ?", ["sss", $params[0], $params[1], $params[2]]);
    }

    public function getRatingFromID($id) 
    {
        return $this->read("SELECT * FROM ratings WHERE id = ?", ["i", $id]);
    }

    public function createRating($params) 
    {
        return $this->cud("INSERT INTO ratings (username, song, artist, rating) VALUES (?, ?, ?, ?)", ["sssi", $params[0], $params[1], $params[2], $params[3]]);
    }

    public function deleteRating($id)
    {
        return $this->cud("DELETE FROM ratings WHERE id = ?", ["i", $id]);
    }

    public function updateRating($params)
    {
        return $this->cud("UPDATE ratings SET song = ?, artist = ?, rating =? WHERE id = ?", ["ssii", $params[0], $params[1], $params[2], $params[3]]);
    }
}