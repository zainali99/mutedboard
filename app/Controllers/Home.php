<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;
use App\Models\Thread;
use App\Models\Group;

class Home extends Controller
{
    /**
     * Show the index page
     */
    public function indexAction()
    {
        $data = [
            'title' => 'MutedBoard - Community Discussions',
            'threads' => Thread::getRecentThreads(12),
            'groups' => Group::getAll(),
            'total_threads' => count(Thread::getAll())
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
