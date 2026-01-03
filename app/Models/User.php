<?php

namespace App\Models;

use Core\Model;
use PDO;

class User extends Model
{
    /**
     * Get all users
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM users ORDER BY created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Find user by ID
     */
    public static function findById($id)
    {
        $db = static::getDB();
        $stmt = $db->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Find user by username
     */
    public static function findByUsername($username)
    {
        $db = static::getDB();
        $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Find user by email
     */
    public static function findByEmail($email)
    {
        $db = static::getDB();
        $stmt = $db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
    
    /**
     * Create a new user
     */
    public static function create($data)
    {
        $db = static::getDB();
        
        $stmt = $db->prepare('
            INSERT INTO users (username, email, password, role)
            VALUES (:username, :email, :password, :role)
        ');
        
        $stmt->bindValue(':username', $data['username'], PDO::PARAM_STR);
        $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindValue(':password', password_hash($data['password'], PASSWORD_DEFAULT), PDO::PARAM_STR);
        $stmt->bindValue(':role', $data['role'] ?? 'user', PDO::PARAM_STR);
        
        $stmt->execute();
        
        return $db->lastInsertId();
    }
    
    /**
     * Update user
     */
    public static function update($id, $data)
    {
        $db = static::getDB();
        
        $fields = [];
        $params = ['id' => $id];
        
        foreach ($data as $key => $value) {
            if (in_array($key, ['username', 'email', 'role', 'is_muted', 'muted_until'])) {
                $fields[] = "$key = :$key";
                $params[$key] = $value;
            }
        }
        
        if (isset($data['password'])) {
            $fields[] = "password = :password";
            $params['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $stmt = $db->prepare($sql);
        
        return $stmt->execute($params);
    }
    
    /**
     * Delete user
     */
    public static function delete($id)
    {
        $db = static::getDB();
        $stmt = $db->prepare('DELETE FROM users WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
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
