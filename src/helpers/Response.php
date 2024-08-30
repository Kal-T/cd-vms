<?php

// Response.php

namespace App\Helpers;

class Response
{
    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }

    public function sendJson(array $data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
