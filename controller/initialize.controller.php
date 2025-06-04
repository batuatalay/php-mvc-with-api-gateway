<?php 
require_once BASE . "/middleware/common.middleware.php";
require_once BASE . "/model/initialize.model.php";

spl_autoload_register( function($className) {
    if($className == "SimpleController") {
        $fullPath = "simple.controller.php";
    } else {
        $extension = ".controller.php";
        $fullPath = strtolower($className) . $extension;
    }
    require_once $fullPath;
});

class Initialize extends SimpleController {
    private static function createInitialUsers($model) {
        $users = [
            [
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin'
            ],
            [
                'username' => 'john_doe',
                'email' => 'john@example.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'role' => 'user'
            ],
            [
                'username' => 'jane_smith',
                'email' => 'jane@example.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'role' => 'user'
            ],
            [
                'username' => 'mike_wilson',
                'email' => 'mike@example.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'role' => 'user'
            ],
            [
                'username' => 'sarah_brown',
                'email' => 'sarah@example.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'role' => 'user'
            ]
        ];

        foreach ($users as $user) {
            $model->createUser($user);
        }

        return count($users);
    }

    public static function getMainPage() {
        try {
            $model = new InitializeModel();
            
            // First check if tables exist
            if ($model->checkIfTablesExist()) {
                header("Location: /main");
                exit;
            }
            
            // Run migrations if tables don't exist
            $messages = $model->runAllMigrations();
            
            // Create initial users
            $userCount = self::createInitialUsers($model);
            $messages[] = "{$userCount} users created successfully.";
            
            echo "<h1>Database Migration</h1>";
            echo "<div style='background-color: #d4edda; color: #155724; padding: 15px; border-radius: 4px;'>";
            echo "<h3>✅ Database tables created successfully!</h3>";
            echo "<ul>";
            foreach ($messages as $message) {
                echo "<li>" . htmlspecialchars($message) . "</li>";
            }
            echo "</ul>";
            echo "<p>You will be redirected to main page in 3 seconds...</p>";
            echo "</div>";
            
            // Redirect to main page after 3 seconds
            echo "<script>setTimeout(function() { window.location.href = '/main'; }, 3000);</script>";
            
        } catch (Exception $e) {
            echo '<div style="color: red; padding: 20px;">
                Error: ' . htmlspecialchars($e->getMessage()) . '
            </div>';
        }
    }

    // This method will perform both Login and Auth checks
    #[LoginAttribute]
    #[AdminAttribute]
    public static function getDashboard() {
        echo 'Dashboard Page<br>';
        exit;
    }
}