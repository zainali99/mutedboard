<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;

class Home extends Controller
{
    /**
     * Show the index page
     */
    public function indexAction()
    {
        $data = [
            'title' => 'Welcome to MutedBoard',
            'framework' => 'MutedBoard MVC Framework',
            'version' => '1.0.0',
            'features' => [
                'Custom Router',
                'MVC Architecture',
                'Template Engine',
                'Database Support',
                'Error Handling'
            ]
        ];

        View::renderWithTemplate('home/index.php', 'default', $data);
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

        View::renderWithTemplate('home/about.php', 'default', $data);
    }
}
