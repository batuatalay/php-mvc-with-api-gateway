<?php

class SessionHelper {
    public static function createUserSession($userData) {
        if(!isset($_SESSION['user'])) {
            $_SESSION['user'] = [
            'id' => $userData['id'] ?? null,
            'name' => $userData['name'] ?? '',
            'username' => $userData['username'] ?? '',
            'user_role' => $userData['user_role'] ?? 'user',
            'permissions' => $userData['permissions'] ?? [],
            'created_at' => date('Y-m-d H:i:s')
        ];
        echo "Test user created and logged in successfully!<br>";
        } else {
            echo 'you are already logIn<br>';
        }
    }

    public static function getUserData() {
        return $_SESSION['user'] ?? null;
    }

    public static function hasPermission($permission) {
        $userData = self::getUserData();
        if (!$userData) return false;
        return in_array($permission, $userData['permissions']);
    }

    public static function getUserRole() {
        $userData = self::getUserData();
        return $userData['user_role'] ?? null;
    }

    public static function isLoggedIn() {
        return isset($_SESSION['user']);
    }

    public static function isAdmin() {
        $userData = self::getUserData();
        if (!$userData) return false;
        return $userData['user_role'] === 'admin';
    }

    public static function destroySession() {
        unset($_SESSION['user']);
        session_destroy();
        echo "Session destroyed<br>";
    }

    public static function changeUser() {
        $_SESSION['user'] = [
            'id' => 2,
            'name' => 'Regular User',
            'username' => 'user',
            'user_role' => 'user',
            'permissions' => ['read']
        ];
        echo "User changed to regular user<br>";
    }
}
function createTestUser() {
    $testUser = [
        'id' => 1,
        'name' => 'Test User',
        'username' => 'testuser',
        'user_role' => 'admin',
        'permissions' => ['read', 'write', 'delete']
    ];
    
    SessionHelper::createUserSession($testUser);
} 