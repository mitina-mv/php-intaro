<?php

namespace App\Controller;

use App\Model\Author;
use App\Model\Book;

use DateTime;
use Exception;

class BookController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->repository = $this->modelManager->getRepository(Book::class);
    }

    public function index()
    {
        $authors = $this->modelManager->getRepository(Author::class)->findAll();

        return $this->render('book/index', ['authors' => $authors]);
    }

    public function create()
    {
        @session_start();

        if(!isset($_SESSION['user']))
            $this->redirect('/book', ["error" => "Вам низзя ничего загружать, попытка плохая"]);  
        
        $request = app()->getRequest();

        // TODO валидация
        if(empty($request->get('name')) 
            || empty($request->get('author_id'))
            || !is_int($request->get('author_id'))
            || empty($_FILES['book'])
            || empty($_FILES['picture'])
        ) {
            $this->redirect('/book', ["error" => "Ошибка в данных, очень грустно, но мне лень писать, где ошибки - они просто есть. В форме все поля обязательные."]);
        }

        $book = new Book();

        // загрузка файлов
        try {
            $book->file_path = loadfile('book', $_SESSION['user']->email, ['pdf', 'doc'])[0];
            $book->picture_path = loadfile('picture', $_SESSION['user']->email)[0];
        } catch(Exception $e) {
            $this->redirect('/book', ["error" => $e->getMessage()]);
        }

        $book->author_id = (int) $request->get('author_id');
        $book->user_id = $_SESSION['user']->id;
        $book->date = new DateTime($request->get('date'));
        $book->name = $request->get('name');
        $book->isdownload = $request->get('isdownload') == "on" ? true : false;
        
        $this->modelManager->persist($book);
        $this->modelManager->flush();

        return $this->redirect('/');
    }
    
    public function read($id)
    {        
        @session_start();

        if(!isset($_SESSION['user']))
            $this->redirect('/book');

        $book = $this->repository->find((int) $id[0]);
        $authors = $this->modelManager->getRepository(Author::class)->findAll();

        return $this->render('book/index', [
            'authors' => $authors,
            'book' => $book
        ]);
    }
}