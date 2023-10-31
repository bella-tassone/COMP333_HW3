<?php
/**
 * UserModel class handles user-related database operations
 * 
 */

require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class UserModel extends Database
{
    // Creates a new user in the database
    public function createUser($username, $password) 
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        return $this->cud("INSERT INTO users (username, password) VALUES (?, ?)", ["ss", $username, $hashed_password]);
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
        $result = $this->read("SELECT * FROM users WHERE username = ?", ["s", $username]);
        return count($result) != 0;
    }
}
?>