<?php

namespace App\Controller;

use App\View\View;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

abstract class Controller
{
    protected $view;
    public $modelManager;
    protected $repository = null;

    public function __construct()
    {
        $this->view = new View();
        $this->modelManager = app()->orm->getModelManager();
    }
    
    public function render($path, $data = [])
    {
        return new Response($this->view->make($path, $data));
    }

    // TODO переделать, чтобы не отдавать get параметры
    public function redirect($path, $data = [])
    {
        $getParams = [];
        foreach($data as $key => $val)
        {
            $getParams[]= "$key=$val";
        }

        return new RedirectResponse("$path?" . implode("&", $getParams));
    }
}