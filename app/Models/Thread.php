<?php

namespace App\Models;

use Core\Model;
use PDO;

class Thread extends Model
{
    /**
     * Get all threads
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('
            SELECT 
                t.*,
                u.username as author_username,
                g.name as group_name
            FROM threads t
            LEFT JOIN users u ON t.user_id = u.id
            LEFT JOIN `groups` g ON t.group_id = g.id
            ORDER BY t.is_pinned DESC, t.created_at DESC
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get recent threads
     */
    public static function getRecentThreads($limit = 10)
    {
        $db = static::getDB();
        $stmt = $db->prepare('
            SELECT 
                t.*,
                u.username as author_username,
                g.name as group_name
            FROM threads t
            LEFT JOIN users u ON t.user_id = u.id
            LEFT JOIN `groups` g ON t.group_id = g.id
            ORDER BY t.is_pinned DESC, t.created_at DESC
            LIMIT :limit
        ');
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find thread by ID
     */
    public static function findById($id)
    {
        $db = static::getDB();
        $stmt = $db->prepare('
            SELECT 
                t.*,
                u.username as author_username,
                u.email as author_email,
                g.name as group_name,
                g.description as group_description
            FROM threads t
            LEFT JOIN users u ON t.user_id = u.id
            LEFT JOIN `groups` g ON t.group_id = g.id
            WHERE t.id = :id
        ');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new thread
     */
    public static function create($data)
    {
        $db = static::getDB();
        $stmt = $db->prepare('
            INSERT INTO threads (group_id, user_id, title, content)
            VALUES (:group_id, :user_id, :title, :content)
        ');
        
        $stmt->bindValue(':group_id', $data['group_id'], PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':title', $data['title'], PDO::PARAM_STR);
        $stmt->bindValue(':content', $data['content'], PDO::PARAM_STR);
        
        $stmt->execute();
        
        return $db->lastInsertId();
    }

    /**
     * Get threads by group
     */
    public static function getByGroup($groupId)
    {
        $db = static::getDB();
        $stmt = $db->prepare('
            SELECT 
                t.*,
                u.username as author_username,
                g.name as group_name
            FROM threads t
            LEFT JOIN users u ON t.user_id = u.id
            LEFT JOIN `groups` g ON t.group_id = g.id
            WHERE t.group_id = :group_id
            ORDER BY t.is_pinned DESC, t.created_at DESC
        ');
        $stmt->bindValue(':group_id', $groupId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get threads by user
     */
    public static function getByUser($userId)
    {
        $db = static::getDB();
        $stmt = $db->prepare('
            SELECT 
                t.*,
                u.username as author_username,
                g.name as group_name
            FROM threads t
            LEFT JOIN users u ON t.user_id = u.id
            LEFT JOIN `groups` g ON t.group_id = g.id
            WHERE t.user_id = :user_id
            ORDER BY t.created_at DESC
        ');
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Increment view count
     */
    public static function incrementViews($id)
    {
        $db = static::getDB();
        $stmt = $db->prepare('
            UPDATE threads 
            SET views = views + 1 
            WHERE id = :id
        ');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Update thread
     */
    public static function update($id, $data)
    {
        $db = static::getDB();
        $stmt = $db->prepare('
            UPDATE threads 
            SET title = :title, content = :content
            WHERE id = :id
        ');
        
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':title', $data['title'], PDO::PARAM_STR);
        $stmt->bindValue(':content', $data['content'], PDO::PARAM_STR);
        
        return $stmt->execute();
    }

    /**
     * Delete thread
     */
    public static function delete($id)
    {
        $db = static::getDB();
        $stmt = $db->prepare('DELETE FROM threads WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Toggle pin status
     */
    public static function togglePin($id)
    {
        $db = static::getDB();
        $stmt = $db->prepare('
            UPDATE threads 
            SET is_pinned = NOT is_pinned 
            WHERE id = :id
        ');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Toggle lock status
     */
    public static function toggleLock($id)
    {
        $db = static::getDB();
        $stmt = $db->prepare('
            UPDATE threads 
            SET is_locked = NOT is_locked 
            WHERE id = :id
        ');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Search threads
     */
    public static function search($query)
    {
        $db = static::getDB();
        $stmt = $db->prepare('
            SELECT 
                t.*,
                u.username as author_username,
                g.name as group_name
            FROM threads t
            LEFT JOIN users u ON t.user_id = u.id
            LEFT JOIN `groups` g ON t.group_id = g.id
            WHERE t.title LIKE :query OR t.content LIKE :query
            ORDER BY t.is_pinned DESC, t.created_at DESC
            LIMIT 50
        ');
        $searchTerm = '%' . $query . '%';
        $stmt->bindValue(':query', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
