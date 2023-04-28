<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use App\Model\User;

class HelloController extends Controller
{
    public function s()
    {
        return $this->render('index', ['title' => 'ddd']);
    }

    public function print($name)
    {
        $user = new User();
        $user->firstname = 'kdld';
        $user->lastname = 'ksdfj';
        $user->email = 'kdfj.ksjdh.asjkdh';
        $user->password = 'dskfj';

        $this->modelManager->persist($user);
        $this->modelManager->flush();
        
        return $this->render('hello', ['e666' => $name]);
    }
}