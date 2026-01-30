<?php
namespace App\Controllers;
use Core\Controller;
use App\Utils\Response;


/**
 * API Controller
 *
 * Handles API endpoints for the application.
 */
class API extends Controller
{
    /**
     * API constructor.
     *
     * @param array $route_params Route parameters from the matched route.
     */
    public function __construct($route_params = [])
    {
        parent::__construct($route_params); // REQUIRED - sets $this->route_params
        // Place API authentication or validation logic here if needed

    }

    /**
     * Example: GET /api/ping
     * Responds with a simple JSON message.
     */
    public function pingAction()
    {
        Response::json(["status"=> "success","message"=> "pong !!!"]);
    }




    public function usersWithFilters()
    {
        // Implementation for fetching users with filters
Response::success("OK");

    }





}