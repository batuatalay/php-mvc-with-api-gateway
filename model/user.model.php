<?php
// model/user.model.php (yeni versiyon)

require_once BASE . '/orm/BaseORM.php';

class User extends BaseORM {
    protected static $table = 'users';
    protected static $primaryKey = 'id';
    protected static $fillable = ['username', 'email', 'role', 'password', 'active'];
    
    // Eski metodların yeni versiyonları
    public static function getAllUsers() {
        return self::orderBy('role')->orderBy('username')->get();
    }
    
    public static function getUserById($id) {
        return self::find($id);
    }
    
    public static function getUserByUsername($username) {
        $users = self::where('username', $username)->get();
        return !empty($users) ? $users[0] : null;
    }
    
    // Yeni metodlar (bonus)
    public static function getActiveUsers() {
        return self::where('active', 1)->get();
    }
    
    public static function getUsersByRole($role) {
        return self::where('role', $role)->get();
    }
    
    public static function getAdmins() {
        return self::where('role', 'admin')->get();
    }
    
    // Instance metodları
    public function isAdmin() {
        return $this->role === 'admin';
    }
    
    public function isActive() {
        return $this->active == 1;
    }
    
    public function activate() {
        $this->active = 1;
        return $this->save();
    }
    
    public function deactivate() {
        $this->active = 0;
        return $this->save();
    }
    
    // Password handling
    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }
    
    public function verifyPassword($password) {
        return password_verify($password, $this->password);
    }
    
    // Custom queries için raw SQL
    public static function getUserStats() {
        return self::raw("
            SELECT 
                role,
                COUNT(*) as count,
                COUNT(CASE WHEN active = 1 THEN 1 END) as active_count
            FROM users 
            GROUP BY role
        ");
    }
    
    // ERP'de muhtemelen lazım olan metodlar
    public static function searchUsers($query) {
        return self::raw("
            SELECT * FROM users 
            WHERE username LIKE ? OR email LIKE ? 
            ORDER BY username
        ", ["%{$query}%", "%{$query}%"]);
    }
    
    public static function getRecentUsers($days = 30) {
        return self::raw("
            SELECT * FROM users 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
            ORDER BY created_at DESC
        ", [$days]);
    }
}