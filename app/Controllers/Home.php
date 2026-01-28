<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;
use Core\Model;
use App\Models\Thread;
use App\Models\Group;

class Home extends Controller
{
    /**
     * Show the index page
     */
    public function indexAction()
    {
        $threads = Thread::getRecentThreads(12);
        $groups = Group::getAll();
        $allThreads = Thread::all();
        
        $data = [
            'title' => 'MutedBoard - Community Discussions',
            'threads' => $threads, // Already returns arrays
            'groups' => $groups, // getAll() now returns arrays
            'total_threads' => count($allThreads)
        ];

        View::renderWithTemplate('home/index', 'default', $data);
    }

    /**
     * Show the about page
     */
    public function aboutAction()
    {
        $data = [
            'title' => 'About MutedBoard',
            'description' => 'A lightweight custom PHP MVC framework'
        ];

        View::renderWithTemplate('home/about', 'default', $data);
    }
}
