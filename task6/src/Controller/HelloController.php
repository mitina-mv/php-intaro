<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class HelloController extends Controller
{
    public function s()
    {
        return $this->render('index', ['title' => 'ddd']);
    }

    public function print($name)
    {
        dump($app->orm);
        return $this->render('hello', ['e666' => $name]);
    }
}