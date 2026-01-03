<?php

namespace App\Models;

use Core\Model;
use PDO;

class Group extends Model
{
    /**
     * Get all groups
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM `groups` ORDER BY name ASC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find group by ID
     */
    public static function findById($id)
    {
        $db = static::getDB();
        $stmt = $db->prepare('SELECT * FROM `groups` WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Find group by slug
     */
    public static function findBySlug($slug)
    {
        $db = static::getDB();
        $stmt = $db->prepare('SELECT * FROM `groups` WHERE slug = :slug');
        $stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new group
     */
    public static function create($data)
    {
        $db = static::getDB();
        $stmt = $db->prepare('
            INSERT INTO `groups` (name, slug, description)
            VALUES (:name, :slug, :description)
        ');
        
        $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindValue(':slug', $data['slug'], PDO::PARAM_STR);
        $stmt->bindValue(':description', $data['description'] ?? '', PDO::PARAM_STR);
        
        $stmt->execute();
        
        return $db->lastInsertId();
    }

    /**
     * Update group
     */
    public static function update($id, $data)
    {
        $db = static::getDB();
        $stmt = $db->prepare('
            UPDATE `groups` 
            SET name = :name, slug = :slug, description = :description
            WHERE id = :id
        ');
        
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindValue(':slug', $data['slug'], PDO::PARAM_STR);
        $stmt->bindValue(':description', $data['description'] ?? '', PDO::PARAM_STR);
        
        return $stmt->execute();
    }

    /**
     * Delete group
     */
    public static function delete($id)
    {
        $db = static::getDB();
        $stmt = $db->prepare('DELETE FROM `groups` WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
