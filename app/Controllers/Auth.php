<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;
use App\Models\User;

class Auth extends Controller
{
    /**
     * Show login form
     */
    public function login()
    {
        // If already logged in, redirect to home
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }
        
        View::render('auth/login', [
            'title' => 'Login',
            'error' => $_SESSION['login_error'] ?? null
        ]);
        
        unset($_SESSION['login_error']);
    }
    
    /**
     * Process login
     */
    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }
        
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        
        if (empty($username) || empty($password)) {
            $_SESSION['login_error'] = 'Please enter both username and password';
            header('Location: /login');
            exit;
        }
        
        $user = User::authenticate($username, $password);
        
        if ($user) {
            // Login successful - convert object to array or access properties
            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;
            $_SESSION['email'] = $user->email;
            $_SESSION['role'] = $user->role;
            $_SESSION['is_muted'] = $user->is_muted;
            
            // Set remember me cookie if checked
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                setcookie('remember_token', $token, time() + (86400 * 30), '/'); // 30 days
                User::saveRememberToken($user->id, $token);
            }
            
            header('Location: /');
            exit;
        } else {
            $_SESSION['login_error'] = 'Invalid username or password';
            header('Location: /login');
            exit;
        }
    }
    
    /**
     * Logout user
     */
    public function logout()
    {
        // Clear remember me cookie
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
        }
        
        // Destroy session
        session_destroy();
        
        header('Location: /login');
        exit;
    }
    
    /**
     * Check if user is logged in
     */
    public static function checkAuth()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }
    
    /**
     * Get current user
     */
    public static function user()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['user_id'])) {
            return [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'email' => $_SESSION['email'],
                'role' => $_SESSION['role'],
                'is_muted' => $_SESSION['is_muted']
            ];
        }
        
        return null;
    }
}
