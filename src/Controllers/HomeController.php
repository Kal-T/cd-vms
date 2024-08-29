<?php

namespace App\Controllers;

use App\Helpers\ViewHelper;

class HomeController
{
    public function index()
    {
        // Render the default home page
        ViewHelper::render('home/index');
    }
}
