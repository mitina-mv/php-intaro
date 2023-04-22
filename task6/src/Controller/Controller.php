<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    public function render($path, $data = [])
    {
        return new Response();
    }
}