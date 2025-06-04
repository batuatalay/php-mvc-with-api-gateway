<?php

class MainModel extends Mysql {
    private $pdo;
    
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
            throw new Exception("Kullanıcıları getirme hatası: " . $e->getMessage());
        }
    }
} 