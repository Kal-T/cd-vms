<?php

namespace App\Controllers;

use App\Helpers\ViewHelper;

class HomeController
{
    public function index()
    {
        // Render the default home page
        $this->viewHelper->render('home/index');
    }
}
