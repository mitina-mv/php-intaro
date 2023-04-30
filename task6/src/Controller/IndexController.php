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
        $books = $this->repository->findAll();

        $users = [];
        foreach($books as $book)
        {
            if(!isset($users[$book->user_id->id]))
            {
                $user = $this->modelManager
                    ->getRepository(User::class)
                    ->findOneBy(["id"=>$book->user_id]);
                
                $users[$user->id] = $user;
            }
        }

        $authors = [];
        foreach($books as $book)
        {
            if(!isset($authors[$book->author_id->id]))
            {
                $authors[$book->author_id->id] = $this->modelManager
                    ->getRepository(Author::class)
                    ->findOneBy(["id"=>$book->author_id->id]);
            }
        }

        return $this->render('index', 
            [
                'books' => $books, 
                'users' => $users, 
                'authors' => $authors
            ]
        );
    }
}