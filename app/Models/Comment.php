<?php

namespace App\Models;

use Core\Model;
use PDO;

class Comment extends Model
{
    /**
     * Get comments for a thread
     * 
     * @param int $threadId Thread ID
     * @param int $limit Maximum number of comments
     * @param int $offset Offset for pagination
     * @return array
     */
    public static function getByThreadId($threadId, $limit = 50, $offset = 0)
    {
        $db = static::getDB();
        $stmt = $db->prepare('
            SELECT 
                c.*,
                u.username as author_username,
                u.id as author_id
            FROM comments c
            LEFT JOIN users u ON c.user_id = u.id
            WHERE c.thread_id = :thread_id
            ORDER BY c.created_at ASC
            LIMIT :limit OFFSET :offset
        ');
        $stmt->bindValue(':thread_id', $threadId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get comment by ID
     * 
     * @param int $id Comment ID
     * @return array|false
     */
    public static function findById($id)
    {
        $db = static::getDB();
        $stmt = $db->prepare('
            SELECT 
                c.*,
                u.username as author_username,
                u.id as author_id
            FROM comments c
            LEFT JOIN users u ON c.user_id = u.id
            WHERE c.id = :id
        ');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new comment
     * 
     * @param array $data Comment data
     * @return int|false Comment ID or false on failure
     */
    public static function create($data)
    {
        $db = static::getDB();
        $stmt = $db->prepare('
            INSERT INTO comments (thread_id, user_id, content, created_at)
            VALUES (:thread_id, :user_id, :content, NOW())
        ');
        
        $stmt->bindValue(':thread_id', $data['thread_id'], PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':content', $data['content'], PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            return $db->lastInsertId();
        }
        
        return false;
    }

    /**
     * Update a comment
     * 
     * @param int $id Comment ID
     * @param string $content New content
     * @return bool
     */
    public static function update($id, $content)
    {
        $db = static::getDB();
        $stmt = $db->prepare('
            UPDATE comments 
            SET content = :content, updated_at = NOW()
            WHERE id = :id
        ');
        
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        
        return $stmt->execute();
    }

    /**
     * Delete a comment
     * 
     * @param int $id Comment ID
     * @return bool
     */
    public static function delete($id)
    {
        $db = static::getDB();
        $stmt = $db->prepare('DELETE FROM comments WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Get comment count for a thread
     * 
     * @param int $threadId Thread ID
     * @return int
     */
    public static function getCountByThreadId($threadId)
    {
        $db = static::getDB();
        $stmt = $db->prepare('SELECT COUNT(*) FROM comments WHERE thread_id = :thread_id');
        $stmt->bindValue(':thread_id', $threadId, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
}
