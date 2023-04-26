<?php

namespace App\Controller;

use App\View\View;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    protected $view;
    
    public function render($path, $data = [])
    {
        return new Response();
    }

    public function __construct()
    {
        $this->view = new View;
    }
}