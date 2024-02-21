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
        $findStr = $_GET['name'] ?? "";
        // Получаем общее количество книг
        $totalBooks = $this->repository->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->where('b.name LIKE :str')
            ->setParameter('str', '%' . $findStr . '%')
            ->getQuery()
            ->getSingleScalarResult();

        // Устанавливаем количество книг на странице
        $booksPerPage = 6;

        // Получаем номер текущей страницы из параметров запроса
        $currentPage = $_GET['page'] ?? 1;

        // Вычисляем смещение (начальный индекс) для выборки данных
        $offset = ($currentPage - 1) * $booksPerPage;

        // Получаем данные для текущей страницы
        $currentPageBooks = $this->repository->createQueryBuilder('b')
            ->where('b.name LIKE :str')
            ->setParameter('str', '%' . $findStr . '%')
            ->orderBy('b.date', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($booksPerPage)
            ->getQuery()
            ->getResult();

        // Генерируем ссылки на другие страницы
        $totalPages = ceil($totalBooks / $booksPerPage);
        $paginationLinks = [];

        if($totalPages > 1) 
        {
            $beginPage = $currentPage > 5 ? $currentPage - 5 : 1;
            $endPage = $totalPages >= 10 ? ($currentPage > 5 ? $currentPage + 5 : 10) : $totalPages;
            
            for ($i = $beginPage; $i <= $endPage; $i++) {
                if($i == $currentPage)
                    $paginationLinks[] = '<span>' . $i . '</span>';
                else
                    $paginationLinks[] = '<a href="?page=' . $i . '&name=' . $findStr . '">' . $i . '</a>';
            }
        }

        return $this->render('index', 
            [
                'books' => $currentPageBooks,
                'links' => $paginationLinks,
                'searchOn' => true,
                'title' => $findStr ? 'Результаты поиска по запросу "' . $findStr . '"' : "Главная"
            ]
        );
    }
}