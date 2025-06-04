<?php

class InitializeModel extends Mysql {
	private $tableName = "migrations";
	private $pdo;
	
	public function __construct($arr = []) {
		$this->pdo = $this->connect();
		foreach ($arr as $key => $value) {
			$this->$key = $value;
		}
	}

	public function createUser($userData) {
		try {
			$sql = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute([
				':username' => $userData['username'],
				':email' => $userData['email'],
				':password' => $userData['password'],
				':role' => $userData['role']
			]);
			return true;
		} catch (PDOException $e) {
			throw new Exception("Error creating user: " . $e->getMessage());
		}
	}

	private function getRequiredTables() {
		$tables = [];
		$migrationsDir = __DIR__ . '/../migrations';
		
		// Read all files in directory
		$files = scandir($migrationsDir);
		
		foreach ($files as $file) {
			// Skip . and .. directories
			if ($file === '.' || $file === '..') {
				continue;
			}
			
			// Process only files containing 'create' and 'table'
			if (strpos($file, 'create') !== false && strpos($file, 'table') !== false) {
				// Remove 'create_' and '_table.sql' parts
				$tableName = str_replace(['create_', '_table.sql'], '', $file);
				// Remove leading numbers and underscore (e.g., 001_users -> users)
				$tableName = preg_replace('/^\d+_/', '', $tableName);
				$tables[] = $tableName;
			}
		}
		
		return $tables;
	}

	public function checkIfTablesExist() {
		try {
			$tables = $this->getRequiredTables();
			
			// Check all tables
			foreach ($tables as $table) {
				$stmt = $this->pdo->query("SHOW TABLES LIKE '{$table}'");
				if ($stmt->rowCount() == 0) {
					// Return false if any table is missing
					return false;
				}
			}
			// Return true if all tables exist
			return true;
		} catch (Exception $e) {
			throw new Exception("Error checking tables: " . $e->getMessage());
		}
	}
	
	public function runAllMigrations() {
		try {
			// First check if tables exist
			if ($this->checkIfTablesExist()) {
				// If all tables exist, redirect to main page
				header("Location: /main");
				exit;
			}

			$messages = [];
			
			// Disable foreign key checks
			$this->pdo->exec("SET FOREIGN_KEY_CHECKS=0");
			$messages[] = "Foreign key checks disabled";
			
			// First drop all existing tables
			$tables = $this->getRequiredTables();
			foreach ($tables as $table) {
				$this->pdo->exec("DROP TABLE IF EXISTS {$table}");
				$messages[] = "{$table} table dropped (if existed)";
			}
			
			// Find and sort migration files
			$migrations = glob(BASE . '/migrations/*.sql');
			sort($migrations);
			
			foreach ($migrations as $migration) {
				$sql = file_get_contents($migration);
				$filename = basename($migration);
				
				$messages[] = "Starting migration: {$filename}";
				
				// Split SQL file into separate commands
				$statements = array_filter(
					array_map('trim', explode(';', $sql)),
					function($sql) { return !empty($sql); }
				);
				
				// Execute each command
				foreach ($statements as $statement) {
					if (!empty(trim($statement))) {
						$this->pdo->exec($statement);
					}
				}
				
				$messages[] = "Migration completed: {$filename}";
			}
			
			// Re-enable foreign key checks
			$this->pdo->exec("SET FOREIGN_KEY_CHECKS=1");
			$messages[] = "Foreign key checks re-enabled";
			
			return $messages;
			
		} catch (Exception $e) {
			// Re-enable foreign key checks in case of error
			$this->pdo->exec("SET FOREIGN_KEY_CHECKS=1");
			throw new Exception("Migration error: " . $e->getMessage());
		}
	}
}