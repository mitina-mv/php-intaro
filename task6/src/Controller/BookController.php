<?php

namespace App\Controller;

use App\Model\User;
use App\Model\Book;
use App\System\App;
use Symfony\Component\HttpFoundation\Request;

class BookController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->repository = $this->modelManager->getRepository(Book::class);
    }

    public function index()
    {
        return $this->render('book/index');
    }
    
    public function read()
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