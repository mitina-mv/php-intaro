<?php

namespace App\Controller;

use App\Model\User;
use App\System\App;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->repository = $this->modelManager->getRepository(User::class);
    }

    public function index()
    {
        return $this->render('auth/index');
    }

    public function auth()
    {
        $request = App::getInctance()->getRequest();

        $user = $this->repository->findOneBy([
            'email' => $request->get("email")
        ]);

        if(!$user)
        {
            return $this->redirect('/auth/index', ['error' => 'Ошибка. Пользователь не найден']);
        }

        if(md5($request->get("password")) == $user->password)
        {
            @session_start();
            $_SESSION['user'] = $user;
            return $this->redirect('/');
        } else {
            return $this->redirect('/auth/index', ['error' => 'Ошибка. Неверный пароль.']);
        }
    }

    public function regindex()
    {
        return $this->render('auth/registration');
    }

    // TODO валидация данных
    public function register()
    {
        $request = App::getInctance()->getRequest();

        if($this->repository->findOneBy([
            'email' => $request->get("email")
        ])) {
            return $this->redirect('/registration', ['error' => 'Ошибка. email должен быть уникальным. Для указанного email уже существует аккаунт.']);
        }
        
        $user = new User();
        $user->email = $request->get("email");
        $user->firstname = $request->get("firstname");
        $user->lastname = $request->get("lastname");
        $user->patronymic = $request->get("patronymic") ?: null;
        $user->password = md5($request->get("password"));

        $this->modelManager->persist($user);
        $this->modelManager->flush();

        @session_start();
        $_SESSION['user'] = $user;

        return $this->redirect('/');
    }

    public function logout()
    {
        @session_start();
        unset($_SESSION['user']);

        return $this->redirect('/');
    }
}