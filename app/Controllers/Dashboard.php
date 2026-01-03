<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;
use App\Models\Thread;
use App\Models\Group;
use App\Models\User;

class Dashboard extends Controller
{
    /**
     * Before filter - require authentication
     */
    public function __construct($route_params = [])
    {
        parent::__construct($route_params);
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    /**
     * Dashboard index page
     */
    public function indexAction()
    {
        $data = [
            'title' => 'Dashboard',
            'user' => User::findById($_SESSION['user_id']),
            'threads' => Thread::getRecentThreads(10),
            'groups' => Group::getAll()
        ];

        View::renderWithTemplate('dashboard/index', 'default', $data);
    }

    /**
     * Show create thread form
     */
    public function createThreadAction()
    {
        $data = [
            'title' => 'Create New Thread',
            'groups' => Group::getAll()
        ];

        View::renderWithTemplate('dashboard/create-thread', 'default', $data);
    }

    /**
     * Store new thread
     */
    public function storeThreadAction()
    {
        // Only accept POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /dashboard/create-thread');
            exit;
        }

        $errors = [];
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $group_id = (int)($_POST['group_id'] ?? 0);

        // Validation
        if (empty($title)) {
            $errors[] = 'Title is required';
        } elseif (strlen($title) > 255) {
            $errors[] = 'Title must be less than 255 characters';
        }

        if (empty($content)) {
            $errors[] = 'Content is required';
        }

        if ($group_id <= 0) {
            $errors[] = 'Please select a valid group';
        }

        // Check if group exists
        $group = Group::findById($group_id);
        if (!$group) {
            $errors[] = 'Selected group does not exist';
        }

        if (empty($errors)) {
            try {
                $threadId = Thread::create([
                    'group_id' => $group_id,
                    'user_id' => $_SESSION['user_id'],
                    'title' => $title,
                    'content' => $content
                ]);

                $_SESSION['success'] = 'Thread created successfully!';
                header('Location: /dashboard/thread/' . $threadId);
                exit;
            } catch (\Exception $e) {
                $errors[] = 'Failed to create thread: ' . $e->getMessage();
            }
        }

        // If there are errors, show the form again with errors
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header('Location: /dashboard/create-thread');
        exit;
    }

    /**
     * Show single thread
     */
    public function threadAction()
    {
        $id = $this->route_params['id'] ?? null;
        
        // log the id for debugging
        error_log("Thread ID: " . $id);


        if (!$id) {
            header('Location: /dashboard');
            exit;
        }

        $thread = Thread::findById($id);
        
        if (!$thread) {
            header('Location: /dashboard');
            exit;
        }

        // Increment view count
        Thread::incrementViews($id);

        $data = [
            'title' => $thread['title'],
            'thread' => $thread
        ];

        View::renderWithTemplate('dashboard/thread', 'default', $data);
    }

    /**
     * Show all threads
     */
    public function threadsAction()
    {
        $data = [
            'title' => 'All Threads',
            'threads' => Thread::getAll()
        ];

        View::renderWithTemplate('dashboard/threads', 'default', $data);
    }
}
