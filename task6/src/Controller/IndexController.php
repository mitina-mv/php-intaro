<?php

namespace App\Controller;


use App\Model\User;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->repository = $this->modelManager->getRepository(User::class);
    }
    
    public function index()
    {        
        return $this->render('index');
    }
}