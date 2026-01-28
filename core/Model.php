<?php

/*
 Core Model Class
 *
 * Provides basic ORM functionalities such as CRUD operations,
 * attribute management, and database interactions using PDO.
 * 
 *  // Find users
    $users = User::filter(['status' => 'active', 'role' => 'admin']);
    $user = User::find(1);
    $activeUsers = User::where('status', 'active');

    // Create and save
    $user = User::create(['name' => 'John', 'email' => 'john@example.com']);

    // Update
    $user->name = 'Jane';
    $user->save();

    // Access attributes
    echo $user->name; // Magic getter




*/


namespace Core;

use PDO;
use PDOException;

abstract class Model
{
    /**
     * Table name (override in child class if needed)
     */
    protected static $table = null;

    /**
     * Primary key column name
     */
    protected static $primaryKey = 'id';

    /**
     * Fillable attributes for mass assignment
     */
    protected static $fillable = [];

    /**
     * Guarded attributes (cannot be mass assigned)
     */
    protected static $guarded = ['id'];

    /**
     * Model attributes
     */
    protected $attributes = [];

    /**
     * Indicates if the model exists in database
     */
    protected $exists = false;

    /**
     * Constructor
     */
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    /**
     * Fill model with attributes
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if ($this->isFillable($key)) {
                $this->attributes[$key] = $value;
            }
        }
        return $this;
    }

    /**
     * Force fill model with attributes (bypass fillable/guarded - for database hydration)
     */
    public function forceFill(array $attributes)
    {
        $this->attributes = array_merge($this->attributes, $attributes);
        return $this;
    }

    /**
     * Check if attribute is fillable
     */
    protected function isFillable($key)
    {
        // If fillable is defined, use it
        if (!empty(static::$fillable)) {
            return in_array($key, static::$fillable);
        }
        // Otherwise, check if not guarded
        return !in_array($key, static::$guarded);
    }

    /**
     * Magic getter for attributes
     */
    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Magic setter for attributes
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Magic isset for attributes
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Get all attributes
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * Get the PDO database connection
     */
    protected static function getDB()
    {
        static $db = null;

        if ($db === null) {
            $config = require dirname(__DIR__) . '/config/database.php';
            
            try {
                $dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'] . ';charset=' . $config['charset'];
                $db = new PDO($dsn, $config['username'], $config['password']);
                
                // Throw an Exception when an error occurs
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                return $db;
            } catch (PDOException $e) {
                throw new \Exception($e->getMessage());
            }
        }

        return $db;
    }

    /**
     * Get the table name
     */
    public static function tableName()
    {
        if (static::$table !== null) {
            return static::$table;
        }
        
        // Default: class name lowercased + 's'
        $class = (new \ReflectionClass(static::class))->getShortName();
        return strtolower($class) . 's';
    }

    /**
     * Get all records
     */
    public static function all()
    {
        $db = static::getDB();
        $table = static::tableName();
        
        $stmt = $db->query("SELECT * FROM `$table`");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map(function($row) {
            $model = new static();
            $model->forceFill($row);
            $model->exists = true;
            return $model;
        }, $rows);
    }

    /**
     * Find record by ID
     */
    public static function find($id)
    {
        $db = static::getDB();
        $table = static::tableName();
        $pk = static::$primaryKey;
        
        $stmt = $db->prepare("SELECT * FROM `$table` WHERE `$pk` = :id LIMIT 1");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        
        $model = new static();
        $model->forceFill($row);
        $model->exists = true;
        return $model;
    }

    /**
     * Filter records by conditions
     * Example: User::filter(['id' => 1, 'status' => 'active'])
     */
    public static function filter(array $conditions)
    {
        $db = static::getDB();
        $table = static::tableName();
        
        $where = [];
        $params = [];
        
        foreach ($conditions as $col => $val) {
            $where[] = "`$col` = :$col";
            $params[":$col"] = $val;
        }
        
        $sql = "SELECT * FROM `$table`";
        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        
        $stmt = $db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function($row) {
            $model = new static();
            $model->forceFill($row);
            $model->exists = true;
            return $model;
        }, $rows);
    }

    /**
     * Find records by column value
     */
    public static function where($column, $value)
    {
        return static::filter([$column => $value]);
    }

    /**
     * Get first record matching conditions
     */
    public static function first(array $conditions = [])
    {
        $results = static::filter($conditions);
        return !empty($results) ? $results[0] : null;
    }

    /**
     * Save the model (insert or update)
     */
    public function save()
    {
        $db = static::getDB();
        $table = static::tableName();
        $pk = static::$primaryKey;
        
        if ($this->exists && isset($this->attributes[$pk])) {
            // Update existing record
            return $this->update($db, $table, $pk);
        } else {
            // Insert new record
            return $this->insert($db, $table, $pk);
        }
    }

    /**
     * Perform insert operation
     */
    protected function insert($db, $table, $pk)
    {
        $columns = [];
        $placeholders = [];
        $params = [];
        
        foreach ($this->attributes as $col => $val) {
            if ($col !== $pk) { // Don't insert primary key
                $columns[] = "`$col`";
                $placeholders[] = ":$col";
                $params[":$col"] = $val;
            }
        }
        
        $sql = "INSERT INTO `$table` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        
        $stmt = $db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        
        $result = $stmt->execute();
        
        if ($result) {
            $this->attributes[$pk] = $db->lastInsertId();
            $this->exists = true;
        }
        
        return $result;
    }

    /**
     * Perform update operation
     */
    protected function update($db, $table, $pk)
    {
        $sets = [];
        $params = [];
        
        foreach ($this->attributes as $col => $val) {
            if ($col !== $pk) {
                $sets[] = "`$col` = :$col";
                $params[":$col"] = $val;
            }
        }
        
        $params[":$pk"] = $this->attributes[$pk];
        
        $sql = "UPDATE `$table` SET " . implode(', ', $sets) . " WHERE `$pk` = :$pk";
        
        $stmt = $db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        
        return $stmt->execute();
    }

    /**
     * Delete the model
     */
    public function delete()
    {
        if (!$this->exists) {
            return false;
        }
        
        $db = static::getDB();
        $table = static::tableName();
        $pk = static::$primaryKey;
        
        if (!isset($this->attributes[$pk])) {
            return false;
        }
        
        $stmt = $db->prepare("DELETE FROM `$table` WHERE `$pk` = :id LIMIT 1");
        $stmt->bindValue(':id', $this->attributes[$pk], PDO::PARAM_INT);
        
        $result = $stmt->execute();
        
        if ($result) {
            $this->exists = false;
        }
        
        return $result;
    }

    /**
     * Create a new record
     */
    public static function create(array $attributes)
    {
        $model = new static($attributes);
        $model->save();
        return $model;
    }

    /**
     * Convert collection of models to array of arrays
     */
    public static function toArrayCollection($models)
    {
        if (empty($models)) {
            return [];
        }
        return array_map(function($model) {
            return is_object($model) ? $model->toArray() : $model;
        }, $models);
    }
}
