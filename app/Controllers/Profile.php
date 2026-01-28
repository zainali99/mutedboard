<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;
use Core\Model;
use App\Models\User;

class Profile extends Controller
{
    // add profile index
    public function indexAction(){
        $user = User::findById($_SESSION['user_id']);
        $data = [
            'title' => 'Profile',
            'user' => $user
        ];

        View::renderWithTemplate('profile/index', 'default', $data);

    }
}
