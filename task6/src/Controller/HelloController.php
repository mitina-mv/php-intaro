<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class HelloController
{
    public function print(Request $request)
    {
        return new Response(
            sprintf("Hello %s", $request->get('name'))
        );
    }
}