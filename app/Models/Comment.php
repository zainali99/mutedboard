<?php

namespace App\Models;

use Core\Model;
use PDO;

class Comment extends Model
{
    protected static $fillable = ['thread_id', 'user_id', 'content'];
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
     * Get comment by ID with author info
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
     * Create a new comment with timestamp
     * 
     * @param array $data Comment data
     * @return Comment|false Comment instance or false on failure
     */
    public static function createComment($data)
    {
        return static::create($data);
    }

    /**
     * Update comment content
     * 
     * @param int $id Comment ID
     * @param string $content New content
     * @return bool
     */
    public static function updateContent($id, $content)
    {
        $comment = static::find($id);
        if (!$comment) {
            return false;
        }
        $comment->content = $content;
        return $comment->save();
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
