<?php

class UserModel extends Mysql {
    protected $pdo;
    
    public function __construct($arr = []) {
        $this->pdo = $this->connect();
        foreach ($arr as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getAllUsers() {
        try {
            $stmt = $this->pdo->query("SELECT username, email, role FROM users ORDER BY role, username");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching users: " . $e->getMessage());
        }
    }

    public function getUserById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT username, email, role FROM users WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }

    public function getUserByUsername($username) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }
} 