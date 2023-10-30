<?php
/**
 * RatingModel class handles rating-related database operations
 * 
 */

require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class RatingModel extends Database
{
    public function getRatingList($limit)
    {
        return $this->read("SELECT * FROM ratings LIMIT ?", ["i", $limit]);
    }

    public function getRating($input)
    {
        return $this->read("SELECT * FROM ratings WHERE username = ? AND song = ? AND artist = ?", ["sss", $input[0], $input[1], $input[2]]);
    }

    public function getRatingFromID($id) 
    {
        return $this->read("SELECT * FROM ratings WHERE id = ?", ["i", $id]);
    }

    public function createRating($username, $artist, $song, $rating)
    {
        return $this->cud("INSERT INTO ratings (username, artist, song, rating) VALUES (?, ?, ?, ?)", ["sssi", $username, $artist, $song, $rating]);

    }

    public function deleteRating($id)
    {
        return $this->cud("DELETE FROM ratings WHERE id = ?", ["i", $id]);
    }

    public function updateRating($input)
    {
        return $this->cud("UPDATE ratings SET song = ?, artist = ?, rating =? WHERE id = ?", ["ssii", $input[0], $input[1], $input[2], $input[3]]);
    }

    public function checkUserCanRate($username, $artist, $song)
    {
        $result = $this->read("SELECT username, artist, song FROM ratings WHERE username = ? AND artist = ? AND song = ?", ["sss", $username, $artist, $song]);
        return (count($result) == 0);
    }

    public function checkUserExists($username)
    {
        $result = $this->read("SELECT * FROM users WHERE username = ?", ["s", $username]);
        return (count($result) != 0);
    }
}
?>