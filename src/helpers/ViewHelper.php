<?php

namespace App\Helpers;

class ViewHelper
{
    public function respondJson($data)
    {
        echo json_encode($data);
        exit;
    }

    public function render($view, $data = [])
    {
        extract($data);
        include __DIR__ . "/../../public/views/{$view}.php";
    }

    public function respondOrRender($view, $data)
    {
        if (strpos($_SERVER['REQUEST_URI'], '/api/') === 0) {
            self::respondJson($data);
        } else {
            self::render($view, $data);
        }
    }
}
