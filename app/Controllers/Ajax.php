<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Thread;
use App\Models\Group;
use App\Models\User;

class Ajax extends Controller
{
    /**
     * Constructor - Set JSON header
     */
    public function __construct()
    {
        header('Content-Type: application/json');
    }

    /**
     * Get threads with filters
     */
    public function getThreadsAction()
    {
        try {
            $groupId = isset($_GET['group_id']) ? (int)$_GET['group_id'] : null;
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';
            $sort = isset($_GET['sort']) ? $_GET['sort'] : 'recent';
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $perPage = 12;
            $offset = ($page - 1) * $perPage;

            // Build query based on filters
            if ($groupId) {
                $threads = Thread::getByGroup($groupId);
            } else {
                $threads = Thread::getAll();
            }

            // Apply search filter
            if (!empty($search)) {
                $threads = array_filter($threads, function($thread) use ($search) {
                    return stripos($thread['title'], $search) !== false || 
                           stripos($thread['content'], $search) !== false;
                });
            }

            // Apply sorting
            switch ($sort) {
                case 'oldest':
                    usort($threads, function($a, $b) {
                        return strtotime($a['created_at']) - strtotime($b['created_at']);
                    });
                    break;
                case 'most_viewed':
                    usort($threads, function($a, $b) {
                        return $b['views'] - $a['views'];
                    });
                    break;
                case 'title':
                    usort($threads, function($a, $b) {
                        return strcmp($a['title'], $b['title']);
                    });
                    break;
                case 'recent':
                default:
                    usort($threads, function($a, $b) {
                        if ($a['is_pinned'] != $b['is_pinned']) {
                            return $b['is_pinned'] - $a['is_pinned'];
                        }
                        return strtotime($b['created_at']) - strtotime($a['created_at']);
                    });
                    break;
            }

            // Pagination
            $total = count($threads);
            $threads = array_slice($threads, $offset, $perPage);

            $this->jsonResponse([
                'success' => true,
                'data' => [
                    'threads' => $threads,
                    'pagination' => [
                        'current_page' => $page,
                        'per_page' => $perPage,
                        'total' => $total,
                        'total_pages' => ceil($total / $perPage)
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single thread
     */
    public function getThreadAction()
    {
        try {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            
            if ($id <= 0) {
                throw new \Exception('Invalid thread ID');
            }

            $thread = Thread::findById($id);
            
            if (!$thread) {
                throw new \Exception('Thread not found');
            }

            $this->jsonResponse([
                'success' => true,
                'data' => $thread
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get all groups
     */
    public function getGroupsAction()
    {
        try {
            $groups = Group::getAll();
            
            $this->jsonResponse([
                'success' => true,
                'data' => $groups
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Increment thread view count
     */
    public function incrementViewAction()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new \Exception('Method not allowed');
            }

            $data = json_decode(file_get_contents('php://input'), true);
            $id = isset($data['id']) ? (int)$data['id'] : 0;

            if ($id <= 0) {
                throw new \Exception('Invalid thread ID');
            }

            Thread::incrementViews($id);

            $this->jsonResponse([
                'success' => true,
                'message' => 'View count incremented'
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Search threads
     */
    public function searchAction()
    {
        try {
            $query = isset($_GET['q']) ? trim($_GET['q']) : '';
            
            if (empty($query)) {
                throw new \Exception('Search query is required');
            }

            $threads = Thread::search($query);

            $this->jsonResponse([
                'success' => true,
                'data' => $threads
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Handle component AJAX requests
     */
    public function componentAction()
    {
        try {
            // Get JSON input
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                $this->jsonResponse([
                    'success' => false,
                    'error' => 'Invalid JSON input'
                ], 400);
            }

            $componentName = $input['component_name'] ?? null;
            $componentId = $input['component_id'] ?? null;
            $method = $input['method'] ?? null;
            $params = $input['params'] ?? [];
            $props = $input['props'] ?? [];

            if (!$componentName || !$method) {
                $this->jsonResponse([
                    'success' => false,
                    'error' => 'Component name and method are required'
                ], 400);
            }

            // Build component class name
            $componentClass = "App\\Components\\{$componentName}";

            // Check if component class exists
            if (!class_exists($componentClass)) {
                $this->jsonResponse([
                    'success' => false,
                    'error' => "Component not found: {$componentName}"
                ], 404);
            }

            // Create component instance
            $component = new $componentClass($props, $componentId);

            // Call the method
            $result = $component->handleAjax($method, $params);

            $this->jsonResponse($result);

        } catch (\Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send JSON response
     */
    private function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }
}
