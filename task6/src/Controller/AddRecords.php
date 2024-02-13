<?php

namespace App\Controller;

use App\Model\Author;
use App\Model\Book;
use App\Model\User;
use App\System\App;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class AddRecords extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->repository = $this->modelManager->getRepository(User::class);
    }

    public function users() {
        // получаем данные
        $names = parse_csv_file($_SERVER['DOCUMENT_ROOT'] . '/assets\data\names.csv');
        $namesTrans = parse_csv_file($_SERVER['DOCUMENT_ROOT'] . '/assets\data\trans_names.csv');
        $surnames = parse_csv_file($_SERVER['DOCUMENT_ROOT'] . '/assets\data\surnames.csv');
        $surnamesTrans = parse_csv_file($_SERVER['DOCUMENT_ROOT'] . '/assets\data\trans_surnames.csv');
        $mails = ['mail.ru', 'yandex.ru', 'yandex.com', 'gmial.com', 'vk.com', 'yahoo.com'];

        $userCount = 2000;

        for($i = 0; $i < $userCount; ++$i)
        {
            $nameIndex = rand(0, count($names) - 1);
            $surnameIndex = rand(0, count($surnames) - 1);
            $mailIndex = rand(0, count($mails) - 1);

            $email = $namesTrans[$nameIndex][0] . "-" . $surnamesTrans[$surnameIndex][0] . '@' . $mails[$mailIndex];

            try {
                $user = new User();
                $user->email = $email;
                $user->firstname = $names[$nameIndex][0];
                $user->lastname = $surnames[$surnameIndex][0];
                $user->patronymic = null;
                $user->password = md5($email);
    
                $this->modelManager->persist($user);
                $this->modelManager->flush();
            } catch (Exception $e) {
                dump('Ошибка добавления пользователя с email = ' . $email);
                --$i;
            }
        }

        return $this->render('additional', ['title' => 'Добавление пользователей']);
    }

    public function authors()
    {
        $authors = parse_csv_file($_SERVER['DOCUMENT_ROOT'] . '/assets\data\authors.csv');

        for($i = 0; $i < count($authors); ++$i)
        {
            try {            
                $author = new Author();
                $author->fio = $authors[$i][0];

                $this->modelManager->persist($author);
                $this->modelManager->flush();
            } catch (Exception $e) {
                dump('Ошибка добавления автора' . $authors[$i][0]);
            }
        }

        return $this->render('additional', ['title' => 'Добавление авторов']);
    }

    public function books()
    {
        $countRecords = rand(1000, 3000);

        // получаем количество пользователей и авторов в БД на текущий момент
        $authorsCount = $this->modelManager->getRepository(Author::class)
            ->createQueryBuilder('a')
            ->select('COUNT(a.id) as cnt')
            ->getQuery()
            ->getResult()[0]['cnt'];

        $usersCount = $this->repository->createQueryBuilder('s')
            ->select('COUNT(s.id) as cnt')
            ->getQuery()
            ->getResult()[0]['cnt'];

        $booknumber = $this->modelManager->getRepository(Book::class)
            ->createQueryBuilder('b')
            ->select('COUNT(b.id) as cnt')
            ->getQuery()
            ->getResult()[0]['cnt'] + 1;

        $bookfiles = glob($_SERVER['DOCUMENT_ROOT'] . '/assets/books/*.jpg');

        for($i = 0; $i < $countRecords; ++$i)
        {
            $user = $this->repository->find(rand(1, $usersCount));
            
            $book = new Book();
            $book->author_id = rand(1, $authorsCount);
            $book->user_id = $user->id;
            $book->name = "Книга {$booknumber}";
            $book->isdownload = (bool) rand(0, 1);

            // рандомная дата
            $int= mt_rand(1262055681,time());
            $book->date = new DateTime(date('Y-m-d', $int));

            // создаем папку пользователя
            if($user->email)
            {
                $dir_name = $_SERVER['DOCUMENT_ROOT'] . '/upload/' . ($user->email);

                if (!is_dir($dir_name)) {
                    mkdir($dir_name, 0777, true);
                }
    
                // забираем фотографию из хранилища заглушек
                $picture_path = '/upload/' . ($user->email) . "/" . time() . "{$i}_.jpg";
                copy($bookfiles[rand(0, count($bookfiles) - 1)], $_SERVER['DOCUMENT_ROOT'] . $picture_path);
    
                $book->picture_path = $picture_path;
    
                // загружаем пустую pdf
                $file_path = '/upload/' . ($user->email) . "/" . time() . '.png';
                copy($_SERVER['DOCUMENT_ROOT'] . '/assets\data\book.pdf', $_SERVER['DOCUMENT_ROOT'] . $file_path);
                $book->file_path = $file_path;
            }

            $this->modelManager->persist($book);
            $this->modelManager->flush();

            ++$booknumber;
        }

        return $this->render('additional', ['title' => 'Добавление книг']);
    }
}