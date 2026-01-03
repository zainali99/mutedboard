<?php

namespace App\Controllers;

use Core\Controller;
use PDO;
use PDOException;

class Install extends Controller
{
    private $config;
    
    public function __construct($route_params)
    {
        parent::__construct($route_params);
        
        // Load database config
        $this->config = require dirname(__DIR__, 2) . '/config/database.php';
        
        // Start session for installer
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Step 1: Welcome screen
     */
    public function index()
    {
        $this->view->render('install/welcome', [
            'title' => 'MutedBoard Installation'
        ]);
    }
    
    /**
     * Step 2: Check requirements
     */
    public function requirements()
    {
        $requirements = [
            'php_version' => [
                'name' => 'PHP Version >= 7.4',
                'required' => true,
                'status' => version_compare(PHP_VERSION, '7.4.0', '>='),
                'current' => PHP_VERSION
            ],
            'pdo' => [
                'name' => 'PDO Extension',
                'required' => true,
                'status' => extension_loaded('pdo'),
                'current' => extension_loaded('pdo') ? 'Installed' : 'Not installed'
            ],
            'pdo_mysql' => [
                'name' => 'PDO MySQL Driver',
                'required' => true,
                'status' => extension_loaded('pdo_mysql'),
                'current' => extension_loaded('pdo_mysql') ? 'Installed' : 'Not installed'
            ],
            'mbstring' => [
                'name' => 'Mbstring Extension',
                'required' => true,
                'status' => extension_loaded('mbstring'),
                'current' => extension_loaded('mbstring') ? 'Installed' : 'Not installed'
            ],
            'writable_logs' => [
                'name' => 'Logs Directory Writable',
                'required' => true,
                'status' => is_writable(dirname(__DIR__, 2) . '/logs'),
                'current' => is_writable(dirname(__DIR__, 2) . '/logs') ? 'Writable' : 'Not writable'
            ]
        ];
        
        $canProceed = true;
        foreach ($requirements as $req) {
            if ($req['required'] && !$req['status']) {
                $canProceed = false;
                break;
            }
        }
        
        $this->view->render('install/requirements', [
            'title' => 'System Requirements',
            'requirements' => $requirements,
            'canProceed' => $canProceed
        ]);
    }
    
    /**
     * Step 3: Database configuration
     */
    public function database()
    {
        $this->view->render('install/database', [
            'title' => 'Database Configuration',
            'config' => $this->config,
            'error' => $_SESSION['db_error'] ?? null
        ]);
        
        unset($_SESSION['db_error']);
    }
    
    /**
     * Test database connection
     */
    public function testConnection()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /install/database');
            exit;
        }
        
        $host = $_POST['host'] ?? 'localhost';
        $dbname = $_POST['dbname'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        try {
            // Try to connect without database first
            $dsn = "mysql:host={$host};charset=utf8mb4";
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Check if database exists
            $stmt = $pdo->query("SHOW DATABASES LIKE '{$dbname}'");
            $dbExists = $stmt->rowCount() > 0;
            
            if (!$dbExists) {
                // Create database
                $pdo->exec("CREATE DATABASE `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            }
            
            // Store config in session
            $_SESSION['db_config'] = [
                'host' => $host,
                'dbname' => $dbname,
                'username' => $username,
                'password' => $password,
                'charset' => 'utf8mb4'
            ];
            
            // Redirect to migration step
            header('Location: /install/migrate');
            exit;
            
        } catch (PDOException $e) {
            $error = $e->getMessage();
            
            // Provide helpful error messages
            if (strpos($error, 'No such file or directory') !== false) {
                $error .= "\n\nTip: Try using '127.0.0.1' instead of 'localhost', or make sure MySQL is running.";
            } elseif (strpos($error, 'Access denied') !== false) {
                $error .= "\n\nTip: Check your username and password are correct.";
            } elseif (strpos($error, 'Connection refused') !== false) {
                $error .= "\n\nTip: Make sure MySQL server is running.";
            }
            
            $_SESSION['db_error'] = $error;
            header('Location: /install/database');
            exit;
        }
    }
    
    /**
     * Step 4: Run migrations
     */
    public function migrate()
    {
        if (!isset($_SESSION['db_config'])) {
            header('Location: /install/database');
            exit;
        }
        
        $errors = [];
        $success = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $config = $_SESSION['db_config'];
            
            try {
                $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
                $pdo = new PDO($dsn, $config['username'], $config['password']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Create users table
                try {
                    $pdo->exec("
                        CREATE TABLE IF NOT EXISTS `users` (
                            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            `username` VARCHAR(50) UNIQUE NOT NULL,
                            `email` VARCHAR(100) UNIQUE NOT NULL,
                            `password` VARCHAR(255) NOT NULL,
                            `role` ENUM('admin', 'moderator', 'user') DEFAULT 'user',
                            `is_muted` BOOLEAN DEFAULT FALSE,
                            `muted_until` DATETIME NULL,
                            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                            INDEX `idx_username` (`username`),
                            INDEX `idx_email` (`email`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                    ");
                    $success[] = 'Users table created successfully';
                } catch (PDOException $e) {
                    $errors[] = 'Users table: ' . $e->getMessage();
                }
                
                // Create groups table
                try {
                    $pdo->exec("
                        CREATE TABLE IF NOT EXISTS `groups` (
                            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            `name` VARCHAR(100) NOT NULL,
                            `description` TEXT,
                            `created_by` INT UNSIGNED NOT NULL,
                            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                            FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE CASCADE,
                            INDEX `idx_name` (`name`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                    ");
                    $success[] = 'Groups table created successfully';
                } catch (PDOException $e) {
                    $errors[] = 'Groups table: ' . $e->getMessage();
                }
                
                // Create threads table
                try {
                    $pdo->exec("
                        CREATE TABLE IF NOT EXISTS `threads` (
                            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            `group_id` INT UNSIGNED NOT NULL,
                            `user_id` INT UNSIGNED NOT NULL,
                            `title` VARCHAR(255) NOT NULL,
                            `content` TEXT NOT NULL,
                            `is_pinned` BOOLEAN DEFAULT FALSE,
                            `is_locked` BOOLEAN DEFAULT FALSE,
                            `views` INT UNSIGNED DEFAULT 0,
                            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                            FOREIGN KEY (`group_id`) REFERENCES `groups`(`id`) ON DELETE CASCADE,
                            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
                            INDEX `idx_group` (`group_id`),
                            INDEX `idx_user` (`user_id`),
                            INDEX `idx_created` (`created_at`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                    ");
                    $success[] = 'Threads table created successfully';
                } catch (PDOException $e) {
                    $errors[] = 'Threads table: ' . $e->getMessage();
                }
                
                // Create posts table
                try {
                    $pdo->exec("
                        CREATE TABLE IF NOT EXISTS `posts` (
                            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            `thread_id` INT UNSIGNED NOT NULL,
                            `user_id` INT UNSIGNED NOT NULL,
                            `content` TEXT NOT NULL,
                            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                            FOREIGN KEY (`thread_id`) REFERENCES `threads`(`id`) ON DELETE CASCADE,
                            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
                            INDEX `idx_thread` (`thread_id`),
                            INDEX `idx_user` (`user_id`),
                            INDEX `idx_created` (`created_at`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                    ");
                    $success[] = 'Posts table created successfully';
                } catch (PDOException $e) {
                    $errors[] = 'Posts table: ' . $e->getMessage();
                }
                
                // Create group_members table
                try {
                    $pdo->exec("
                        CREATE TABLE IF NOT EXISTS `group_members` (
                            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            `group_id` INT UNSIGNED NOT NULL,
                            `user_id` INT UNSIGNED NOT NULL,
                            `role` ENUM('admin', 'moderator', 'member') DEFAULT 'member',
                            `joined_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            FOREIGN KEY (`group_id`) REFERENCES `groups`(`id`) ON DELETE CASCADE,
                            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
                            UNIQUE KEY `unique_membership` (`group_id`, `user_id`),
                            INDEX `idx_group` (`group_id`),
                            INDEX `idx_user` (`user_id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                    ");
                    $success[] = 'Group members table created successfully';
                } catch (PDOException $e) {
                    $errors[] = 'Group members table: ' . $e->getMessage();
                }

                // Create default admin user and group
                try {
                    // Check if admin user already exists
                    $stmt = $pdo->query("SELECT COUNT(*) FROM `users` WHERE `username` = 'admin'");
                    $adminExists = $stmt->fetchColumn() > 0;
                    
                    if (!$adminExists) {
                        // Create default admin user
                        $defaultPassword = password_hash('admin123', PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare("
                            INSERT INTO `users` (`username`, `email`, `password`, `role`)
                            VALUES ('admin', 'admin@mutedboard.local', :password, 'admin')
                        ");
                        $stmt->execute(['password' => $defaultPassword]);
                        $adminUserId = $pdo->lastInsertId();
                        $success[] = 'Default admin user created (username: admin, password: admin123)';
                        
                        // Create default group
                        $stmt = $pdo->prepare("
                            INSERT INTO `groups` (`name`, `description`, `created_by`)
                            VALUES ('General Discussion', 'Default discussion group', :created_by)
                        ");
                        $stmt->execute(['created_by' => $adminUserId]);
                        $groupId = $pdo->lastInsertId();
                        $success[] = 'Default group created';
                        
                        // Add admin to the group as admin
                        $stmt = $pdo->prepare("
                            INSERT INTO `group_members` (`group_id`, `user_id`, `role`)
                            VALUES (:group_id, :user_id, 'admin')
                        ");
                        $stmt->execute([
                            'group_id' => $groupId,
                            'user_id' => $adminUserId
                        ]);
                        $success[] = 'Admin added to default group';
                    }
                } catch (PDOException $e) {
                    $errors[] = 'Default data creation: ' . $e->getMessage();
                }
          










                
                if (empty($errors)) {
                    // Update database config file
                    $this->updateDatabaseConfig($config);
                    $_SESSION['install_complete'] = true;
                    header('Location: /install/complete');
                    exit;
                }
                
            } catch (PDOException $e) {
                $errors[] = 'Connection error: ' . $e->getMessage();
            }
        }
        
        $this->view->render('install/migrate', [
            'title' => 'Database Migration',
            'errors' => $errors,
            'success' => $success
        ]);
    }
    
    /**
     * Step 5: Installation complete
     */
    public function complete()
    {
        if (!isset($_SESSION['install_complete'])) {
            header('Location: /install');
            exit;
        }
        
        // Create .installed file to prevent re-installation
        file_put_contents(dirname(__DIR__, 2) . '/.installed', date('Y-m-d H:i:s'));
        
        // Clear session
        session_destroy();
        
        $this->view->render('install/complete', [
            'title' => 'Installation Complete'
        ]);
    }
    
    /**
     * Update database config file
     */
    private function updateDatabaseConfig($config)
    {
        $configFile = dirname(__DIR__, 2) . '/config/database.php';
        $content = "<?php\n\nreturn [\n";
        $content .= "    'host' => '{$config['host']}',\n";
        $content .= "    'dbname' => '{$config['dbname']}',\n";
        $content .= "    'username' => '{$config['username']}',\n";
        $content .= "    'password' => '{$config['password']}',\n";
        $content .= "    'charset' => '{$config['charset']}'\n";
        $content .= "];\n";
        
        file_put_contents($configFile, $content);
    }
}
