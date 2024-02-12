<?php

namespace App\Controller;

use App\Model\User;
use App\System\App;
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
}