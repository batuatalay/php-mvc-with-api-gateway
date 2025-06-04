<?php
// orm/BaseORM.php

abstract class BaseORM extends Mysql {
    protected static $table;
    protected static $primaryKey = 'id';
    protected static $fillable = [];
    protected $attributes = [];
    protected $exists = false;
    protected static $connection = null;
    protected static $query = [];
    
    public function __construct($attributes = []) {
        if (self::$connection === null) {
            self::$connection = $this->connect();
        }
        $this->pdo = self::$connection;
        $this->fill($attributes);
    }
    
    // Attribute management
    protected function fill($attributes) {
        foreach ($attributes as $key => $value) {
            if (empty(static::$fillable) || in_array($key, static::$fillable)) {
                $this->attributes[$key] = $value;
            }
        }
        return $this;
    }
    
    public function __get($key) {
        return $this->attributes[$key] ?? null;
    }
    
    public function __set($key, $value) {
        if (empty(static::$fillable) || in_array($key, static::$fillable)) {
            $this->attributes[$key] = $value;
        }
    }
    
    // Static Query Methods
    public static function all() {
        $instance = new static();
        $sql = "SELECT * FROM " . static::$table;
        
        try {
            $stmt = $instance->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching records: " . $e->getMessage());
        }
    }
    
    public static function find($id) {
        $instance = new static();
        $sql = "SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?";
        
        try {
            $stmt = $instance->pdo->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                $model = new static($result);
                $model->exists = true;
                return $model;
            }
            return null;
        } catch (PDOException $e) {
            throw new Exception("Error finding record: " . $e->getMessage());
        }
    }
    
    public static function where($column, $operator = '=', $value = null) {
        $instance = new static();
        
        // where('name', 'John') syntax için
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $sql = "SELECT * FROM " . static::$table . " WHERE {$column} {$operator} ?";
        
        try {
            $stmt = $instance->pdo->prepare($sql);
            $stmt->execute([$value]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $models = [];
            foreach ($results as $result) {
                $model = new static($result);
                $model->exists = true;
                $models[] = $model;
            }
            
            return $models;
        } catch (PDOException $e) {
            throw new Exception("Error in where query: " . $e->getMessage());
        }
    }
    
    public static function first() {
        $instance = new static();
        $sql = "SELECT * FROM " . static::$table . " LIMIT 1";
        
        try {
            $stmt = $instance->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                $model = new static($result);
                $model->exists = true;
                return $model;
            }
            return null;
        } catch (PDOException $e) {
            throw new Exception("Error fetching first record: " . $e->getMessage());
        }
    }
    
    // Instance Methods
    public function save() {
        if ($this->exists) {
            return $this->update();
        } else {
            return $this->create();
        }
    }
    
    protected function create() {
        $columns = array_keys($this->attributes);
        $placeholders = array_fill(0, count($columns), '?');
        
        $sql = "INSERT INTO " . static::$table . " (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute(array_values($this->attributes));
            
            if ($result) {
                $this->attributes[static::$primaryKey] = $this->pdo->lastInsertId();
                $this->exists = true;
            }
            
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Error creating record: " . $e->getMessage());
        }
    }
    
    protected function update() {
        if (!isset($this->attributes[static::$primaryKey])) {
            throw new Exception("Cannot update record without primary key");
        }
        
        $updates = [];
        $values = [];
        
        foreach ($this->attributes as $key => $value) {
            if ($key !== static::$primaryKey) {
                $updates[] = "{$key} = ?";
                $values[] = $value;
            }
        }
        
        $values[] = $this->attributes[static::$primaryKey];
        
        $sql = "UPDATE " . static::$table . " SET " . implode(', ', $updates) . " WHERE " . static::$primaryKey . " = ?";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($values);
        } catch (PDOException $e) {
            throw new Exception("Error updating record: " . $e->getMessage());
        }
    }
    
    public function delete() {
        if (!$this->exists || !isset($this->attributes[static::$primaryKey])) {
            throw new Exception("Cannot delete record without primary key");
        }
        
        $sql = "DELETE FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$this->attributes[static::$primaryKey]]);
            
            if ($result) {
                $this->exists = false;
            }
            
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Error deleting record: " . $e->getMessage());
        }
    }
    
    public static function destroy($id) {
        $model = static::find($id);
        return $model ? $model->delete() : false;
    }
    
    // Utility Methods
    public function toArray() {
        return $this->attributes;
    }
    
    public function toJson() {
        return json_encode($this->attributes);
    }
    
    public function exists() {
        return $this->exists;
    }
    
    // Advanced Query Methods
    public static function orderBy($column, $direction = 'ASC') {
        $instance = new static();
        if (!isset(static::$query['orderBy'])) {
            static::$query['orderBy'] = [];
        }
        static::$query['orderBy'][] = "{$column} {$direction}";
        return new static();
    }
    
    public static function get() {
        $instance = new static();
        $sql = "SELECT * FROM " . static::$table;

        if (!empty(static::$query['orderBy'])) {
            $sql .= " ORDER BY " . implode(', ', static::$query['orderBy']);
        }

        try {
            $stmt = $instance->pdo->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Reset query builder
            static::$query = [];
            
            $models = [];
            foreach ($results as $result) {
                $model = new static($result);
                $model->exists = true;
                $models[] = $model;
            }
            
            return $models;
        } catch (PDOException $e) {
            throw new Exception("Error in query: " . $e->getMessage());
        }
    }
    
    public static function limit($count) {
        $instance = new static();
        $sql = "SELECT * FROM " . static::$table . " LIMIT ?";
        
        try {
            $stmt = $instance->pdo->prepare($sql);
            $stmt->execute([$count]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $models = [];
            foreach ($results as $result) {
                $model = new static($result);
                $model->exists = true;
                $models[] = $model;
            }
            
            return $models;
        } catch (PDOException $e) {
            throw new Exception("Error in limit query: " . $e->getMessage());
        }
    }
    
    // Raw SQL method for complex queries
    public static function raw($sql, $params = []) {
        $instance = new static();
        
        try {
            $stmt = $instance->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error in raw query: " . $e->getMessage());
        }
    }
}