<?php

namespace App\Models;

use Core\Model;
use PDO;

class User extends Model
{
    protected static $fillable = ['username', 'email', 'password', 'role', 'is_muted', 'muted_until'];
    protected static $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Get all users ordered by creation date (returns arrays for views)
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM users ORDER BY created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Find user by ID (alias for find())
     */
    public static function findById($id)
    {
        return static::find($id);
    }
    
    /**
     * Find user by username
     */
    public static function findByUsername($username)
    {
        return static::first(['username' => $username]);
    }
    
    /**
     * Find user by email
     */
    public static function findByEmail($email)
    {
        return static::first(['email' => $email]);
    }
    
    /**
     * Authenticate user
     */
    public static function authenticate($username, $password)
    {
        $user = static::findByUsername($username);
        
        if (!$user) {
            // Also try email
            $user = static::findByEmail($username);
        }

        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        
        return false;
    }
    
    /**
     * Create a new user with hashed password
     */
    public static function createUser($data)
    {
        // Hash password before creating
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        if (!isset($data['role'])) {
            $data['role'] = 'user';
        }
        return static::create($data);
    }
    
    /**
     * Update user with optional password hashing
     */
    public function updateUser($data)
    {
        // Hash password if being updated
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        // Fill and save
        $this->fill($data);
        return $this->save();
    }
    
    /**
     * Save remember token
     */
    public static function saveRememberToken($userId, $token)
    {
        // For now, just store in session
        // In production, store hashed token in database
        $_SESSION['remember_token'] = password_hash($token, PASSWORD_DEFAULT);
    }
    
    /**
     * Check if user is muted
     */
    public static function isMuted($userId)
    {
        $user = static::findById($userId);
        
        if (!$user) {
            return false;
        }
        
        if ($user['is_muted']) {
            // Check if mute has expired
            if ($user['muted_until'] && strtotime($user['muted_until']) < time()) {
                // Unmute user
                static::update($userId, ['is_muted' => false, 'muted_until' => null]);
                return false;
            }
            return true;
        }
        
        return false;
    }
}
