<?php

namespace App\Helpers;

class ViewHelper
{
    public static function respondJson($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function render($view, $data = [])
    {
        extract($data);
        include __DIR__ . "/../../public/views/{$view}.php";
    }

    public static function respondOrRender($view, $data)
    {
        if (strpos($_SERVER['REQUEST_URI'], '/api/') === 0) {
            self::respondJson($data);
        } else {
            self::render($view, $data);
        }
    }
}
