<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class HelloController extends Controller
{
    public function print()
    {
        return new Response(
            sprintf("Hello %s", 'ss')
        );
    }
}