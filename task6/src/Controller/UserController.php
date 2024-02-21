<?php

namespace App\Controller;

use App\Model\Author;
use App\Model\Book;
use App\Model\User;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->repository = $this->modelManager->getRepository(Book::class);
    }
    
    public function index()
    {
        @session_start();

        if(!isset($_SESSION['user']))
            $this->redirect('/', ["error" => "Вы не авторизованы, не можем показать вашу страницу"]);

        // Получаем общее количество книг
        $totalBooks = $this->repository->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->where('b.user_id = :userId')
            ->setParameter('userId', $_SESSION['user']->id)
            ->getQuery()
            ->getSingleScalarResult();

        // Устанавливаем количество книг на странице
        $booksPerPage = 12;

        // Получаем номер текущей страницы из параметров запроса
        $currentPage = $_GET['page'] ?? 1;

        // Вычисляем смещение (начальный индекс) для выборки данных
        $offset = ($currentPage - 1) * $booksPerPage;

        // Получаем данные для текущей страницы
        $currentPageBooks = $this->repository->createQueryBuilder('b')
            ->where('b.user_id = :userId')
            ->setParameter('userId', $_SESSION['user']->id)
            ->orderBy('b.date', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($booksPerPage)
            ->getQuery()
            ->getResult();

        // Генерируем ссылки на другие страницы
        $paginationLinks = [];
        $totalPages = ceil($totalBooks / $booksPerPage);

        if($totalPages > 1) 
        {
            $beginPage = $currentPage > 5 ? $currentPage - 5 : 1;
            $endPage = $totalPages >= 10 ? ($currentPage > 5 ? $currentPage + 5 : 10) : $totalPages;
            
            for ($i = $beginPage; $i <= $endPage; $i++) {
                if($i == $currentPage)
                    $paginationLinks[] = '<span>' . $i . '</span>';
                else
                    $paginationLinks[] = '<a href="?page=' . $i . '">' . $i . '</a>';
            }
        }

        return $this->render('index', 
            [
                'books' => $currentPageBooks,
                'links' => $paginationLinks,
                'searchOn' => false,
                'title' => "Моя полка"
            ]
        );
    }
    
    public function user($id)
    {
        // Получаем общее количество книг
        $totalBooks = $this->repository->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->where('b.user_id = :userId')
            ->setParameter('userId', $id[0])
            ->getQuery()
            ->getSingleScalarResult();
        // Устанавливаем количество книг на странице
        $booksPerPage = 12;

        $totalPages = ceil($totalBooks / $booksPerPage);

        // Получаем номер текущей страницы из параметров запроса
        $currentPage = $_GET['page'] ?? 1;

        if($totalPages < $currentPage)
        {
            return $this->redirect('/user/' . $id[0] , ["error" => "Запрашиваемая вами страница просто не существует"]);  
        }

        // Вычисляем смещение (начальный индекс) для выборки данных
        $offset = ($currentPage - 1) * $booksPerPage;

        // Получаем данные для текущей страницы
        $currentPageBooks = $this->repository->createQueryBuilder('b')
            ->where('b.user_id = :userId')
            ->setParameter('userId', $id)
            ->orderBy('b.date', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($booksPerPage)
            ->getQuery()
            ->getResult();

        // Генерируем ссылки на другие страницы
        $paginationLinks = [];

        if($totalPages > 1) 
        {
            echo 'fff' . $totalPages;
            $beginPage = $currentPage > 5 ? $currentPage - 5 : 1;
            $endPage = $totalPages >= 10 ? ($currentPage > 5 ? $currentPage + 5 : 10) : $totalPages;
            
            for ($i = $beginPage; $i <= $endPage; $i++) {
                if($i == $currentPage)
                    $paginationLinks[] = '<span>' . $i . '</span>';
                else
                    $paginationLinks[] = '<a href="?page=' . $i . '">' . $i . '</a>';
            }
        }
        $user = $currentPageBooks[0]->getUser();

        return $this->render('index', 
            [
                'books' => $currentPageBooks,
                'links' => $paginationLinks,
                'title' => "Полка пользователя " . $user->firstname . " " .$user->lastname,
                'searchOn' => false,
            ]
        );
    }
}