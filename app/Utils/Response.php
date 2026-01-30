<?php

namespace App\Utils;

class Response
{
    /**
     * Send a JSON response with given data and HTTP status code.
     *
     * @param array $data
     * @param int $status
     */
    public static function json(array $data = [], int $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * Send a JSON success response with optional message and data.
     *
     * @param string $message
     * @param array $data
     * @param int $status
     */
    public static function success(string $message = 'OK', array $data = [], int $status = 200)
    {
        self::json([
            'success' => true,
            'message' => $message,
            'data'    => $data
        ], $status);
    }

    /**
     * Send a JSON error response with message and optional errors array.
     *
     * @param string $message
     * @param array $errors
     * @param int $status
     */
    public static function error(string $message = 'Error', array $errors = [], int $status = 400)
    {
        self::json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors
        ], $status);
    }
}