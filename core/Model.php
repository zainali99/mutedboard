<?php

namespace Core;

use PDO;
use PDOException;

abstract class Model
{
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
}
