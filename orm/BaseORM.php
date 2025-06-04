<?php
require_once BASE . '/helper/Database.php';

class BaseORM {
    protected static $table;
    protected static $primaryKey = 'id';
    protected $attributes = [];
    protected $hidden = [];
    
    public function __construct($attributes = []) {
        $this->attributes = $attributes;
    }
    
    public function __get($name) {
        return $this->attributes[$name] ?? null;
    }
    
    public function __set($name, $value) {
        $this->attributes[$name] = $value;
    }
    
    public function save() {
        if (isset($this->attributes[static::$primaryKey])) {
            return $this->update();
        }
        return $this->insert();
    }
    
    protected function insert() {
        $columns = implode(', ', array_keys($this->attributes));
        $values = implode(', ', array_fill(0, count($this->attributes), '?'));
        
        $sql = "INSERT INTO " . static::$table . " ($columns) VALUES ($values)";
        
        $db = Database::getInstance();
        $stmt = $db->query($sql, array_values($this->attributes));
        
        if ($stmt) {
            $this->attributes[static::$primaryKey] = $db->getConnection()->lastInsertId();
            return true;
        }
        return false;
    }
    
    protected function update() {
        $sets = [];
        $params = [];
        foreach ($this->attributes as $column => $value) {
            if ($column !== static::$primaryKey) {
                $sets[] = "$column = ?";
                $params[] = $value;
            }
        }
        $params[] = $this->attributes[static::$primaryKey];
        
        $sql = "UPDATE " . static::$table . " SET " . implode(', ', $sets) . 
               " WHERE " . static::$primaryKey . " = ?";
        
        return Database::getInstance()->query($sql, $params) !== false;
    }
    
    public static function where($column, $value) {
        $sql = "SELECT * FROM " . static::$table . " WHERE $column = ?";
        
        $stmt = Database::getInstance()->query($sql, [$value]);
        $results = [];
        
        if ($stmt) {
            while ($row = $stmt->fetch()) {
                $results[] = new static($row);
            }
        }
        
        return $results;
    }
    
    public static function find($id) {
        $sql = "SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?";
        
        $stmt = Database::getInstance()->query($sql, [$id]);
        if ($stmt && $row = $stmt->fetch()) {
            return new static($row);
        }
        return null;
    }
    
    public function delete() {
        if (!isset($this->attributes[static::$primaryKey])) {
            return false;
        }
        
        $sql = "DELETE FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?";
        
        return Database::getInstance()->query($sql, [$this->attributes[static::$primaryKey]]) !== false;
    }
} 