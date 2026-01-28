<?php

namespace App\Models;

use Core\Model;
use PDO;

class Group extends Model
{
    protected static $fillable = ['name', 'slug', 'description'];

    /**
     * Get all groups ordered by name (returns arrays for views)
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM `groups` ORDER BY name ASC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find group by ID (alias for find())
     */
    public static function findById($id)
    {
        return static::find($id);
    }

    /**
     * Find group by slug
     */
    public static function findBySlug($slug)
    {
        return static::first(['slug' => $slug]);
    }
}
