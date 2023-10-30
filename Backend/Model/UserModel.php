<?php
/**
 * UserModel class handles user-related database operations
 * 
 */

require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class UserModel extends Database
{
    // Creates a new user in the database
    public function createUser($userData) 
    {
        $hashed_password = password_hash($userData[1], PASSWORD_DEFAULT);
        return $this->cud("INSERT INTO users (username, password) VALUES (?, ?)", ["ss", $userData[0], $hashed_password]);
    }

    public function getUserPassword($username)
    {
        return $this->read("SELECT password FROM users WHERE username = ?", ["s", $username]);
    }

    public function getUserList($limit)
    {
        return $this->read("SELECT * FROM users LIMIT ?", ["i", $limit]);
    }

    public function deleteUser($id)
    {
        return $this->cud("DELETE FROM users WHERE id = ?", ["i", $id]);
    }

    public function checkUserExists($username)
    {
        $result = $this->select("SELECT * FROM users WHERE username = ?", ["s", $username]);
        return count($result) != 0;
    }

    public function checkUserCanRate($username)
    {
        // True if not rated
        $result = $this->select("SELECT username, artist, song FROM ratings WHERE username = ? AND artist = ? AND song = ?", ["sss", $username, $artist, $song]);
        return (count($result) == 0);
    }

}
?>