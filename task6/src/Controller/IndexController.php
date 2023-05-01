<?php

namespace App\Controller;

use App\Model\Author;
use App\Model\Book;
use App\Model\User;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->repository = $this->modelManager->getRepository(Book::class);
    }
    
    public function index()
    {        
        @session_start();

        // для авториз. польз. запрашиваем только его книги в порядке прочтения
        if(isset($_SESSION['user']))
        {
            $books = $this->repository->findBy(
                ['user_id' => $_SESSION['user']->id],
                ['date' => 'ASC']
            );
        } 
        // для неавторизованных - получаем 15 последних прочитанных книг всех пользовтелей
        else 
        { 
            $books = $this->repository->findBy(
                [], 
                ['date' => 'ASC'], 
                15
            );
        }
    
        return $this->render('index', 
            [
                'books' => $books,
            ]
        );
    }
}