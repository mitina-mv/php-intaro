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

        return $this->render('book/index', [
            'authors' => $authors,
            'title' => 'Добавление книги'
        ]);
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
            $book->file_path = loadfile(
                'book', 
                $_SESSION['user']->email, 
                explode(",", $_ENV['ALLOWED_FORMAT_BOOK'])
            )[0];

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
        {
            $this->redirect('/', ["error" => "Для редактрования нужна авторизация."]);
        }

        $book = $this->repository->find((int) $id[0]);

        if($book->user_id != $_SESSION['user']->id) 
        {
            return $this->redirect('/profile', ["error" => "У вас нет прав редактировать эту запись"]);
        }

        $authors = $this->modelManager->getRepository(Author::class)->findAll();

        return $this->render('book/index', [
            'authors' => $authors,
            'book' => $book,
            'title' => 'Редактирование: '. $book->name
        ]);
    }

    public function update($id)
    {
        @session_start();
        
        $request = app()->getRequest();
        $book = $this->repository->find((int) $id[0]);

        if(empty($book)){
            return $this->redirect('/book/' . $id[0], ["error" => "Неизвестная книга, ID не найден"]);
        }

        if($book->user_id != $_SESSION['user']->id){
            return $this->redirect('/profile', ["error" => "У вас нет прав редактировать эту запись"]);
        }

        $book->name = $request->get('name');
        $book->date = new DateTime($request->get('date'));
        $book->isdownload = $request->get('isdownload') == "on" ? true : false;
        $book->author_id = (int) $request->get('author_id');

        // обновление файлов
        // удаляем предыдущие, загружаем новые, если есть
        try {            
            if($_FILES['picture'] && $_FILES['picture']['error'] == 0)
            {
                unlink($_SERVER['DOCUMENT_ROOT'] . $book->picture_path);
                $book->picture_path = loadfile('picture', $_SESSION['user']->email)[0];
            }

            if($_FILES['book'] && $_FILES['book']['error'] == 0)
            {
                unlink($_SERVER['DOCUMENT_ROOT'] . $book->file_path);
                $book->file_path = loadfile(
                    'book', 
                    $_SESSION['user']->email, 
                    explode(",", $_ENV['ALLOWED_FORMAT_BOOK'])
                )[0];
            }

        } catch(Exception $e) {
            $this->redirect('/book/'. $id[0], ["error" => $e->getMessage()]);
        }
        
        $this->modelManager->flush();

        return $this->redirect('/book/' . $id[0]);
    }

    public function delete($id)
    {
        @session_start();

        $book = $this->repository->find((int) $id[0]);

        if($book->user_id != $_SESSION['user']->id){
            return $this->redirect('/', ["error" => "У вас нет прав удалять эту запись"]);
        }

        // удаление файлов
        unlink($_SERVER['DOCUMENT_ROOT'] . $book->picture_path);
        unlink($_SERVER['DOCUMENT_ROOT'] . $book->file_path);

        $this->modelManager->remove($book);
        $this->modelManager->flush();

        return $this->redirect('/');
    }
}