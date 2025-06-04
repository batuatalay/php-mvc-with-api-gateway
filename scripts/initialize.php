<?php
define('BASE', dirname(__DIR__));

// Database configuration
$config = [
    'host' => 'localhost',
    'database' => 'naked-db',
    'username' => 'root',
    'password' => '1q2w3e4r'
];

try {
    // Create database connection
    $dsn = sprintf(
        "mysql:host=%s;charset=utf8mb4",
        $config['host']
    );
    
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$config['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database '{$config['database']}' created or already exists.\n";
    
    // Select database
    $pdo->exec("USE `{$config['database']}`");
    
    // Get all migration files
    $migrations = glob(BASE . '/migrations/*.sql');
    sort($migrations); // Sort by filename to ensure correct order
    
    // Execute each migration file
    foreach ($migrations as $migration) {
        $sql = file_get_contents($migration);
        echo "Executing migration: " . basename($migration) . "\n";
        
        // Split SQL file into individual statements
        $statements = array_filter(
            array_map('trim', explode(';', $sql)),
            function($sql) { return !empty($sql); }
        );
        
        // Execute each statement
        foreach ($statements as $statement) {
            $pdo->exec($statement);
        }
        
        echo "Migration completed: " . basename($migration) . "\n";
    }
    
    echo "\nAll migrations completed successfully!\n";
    
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage() . "\n");
} 