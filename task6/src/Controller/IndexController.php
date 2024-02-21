<?php

namespace App\Controller;

use App\Model\Author;
use App\Model\Book;
use App\Model\User;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->repository = $this->modelManager->getRepository(Book::class);
    }
    
    public function index()
    {        
        // Получаем общее количество книг
        $totalBooks = $this->repository->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->getQuery()
            ->getSingleScalarResult();

        // Устанавливаем количество книг на странице
        $booksPerPage = 10;

        // Получаем номер текущей страницы из параметров запроса
        $currentPage = $_GET['page'] ?? 1;

        // Вычисляем смещение (начальный индекс) для выборки данных
        $offset = ($currentPage - 1) * $booksPerPage;

        // Получаем данные для текущей страницы
        $currentPageBooks = $this->repository->createQueryBuilder('b')
            ->orderBy('b.date', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($booksPerPage)
            ->getQuery()
            ->getResult();

        // Генерируем ссылки на другие страницы
        $totalPages = ceil($totalBooks / $booksPerPage);
        $paginationLinks = [];
        $beginPage = $currentPage > 5 ? $currentPage - 5 : 1;
        $endPage = $currentPage > 5 ? $currentPage + 5 : 10;
        for ($i = $beginPage; $i <= $endPage; $i++) {
            if($i == $currentPage)
                $paginationLinks[] = '<span>' . $i . '</span>';
            else
                $paginationLinks[] = '<a href="?page=' . $i . '">' . $i . '</a>';
        }

        return $this->render('index', 
            [
                'books' => $currentPageBooks,
                'links' => $paginationLinks,
                'title' => "Главная"
            ]
        );
    }
}