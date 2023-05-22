<?php @session_start();?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/templates/style.css">
    <title><?= isset($title) ? $title : ''?></title>
</head>
<body>
    <header>
        <img src="/assets/logo.png" alt="logo" class="header__logo">

        <nav class='header__main-menu main-menu'>
            <a class='main-menu__link' href="/">Главная</a>
            <a class='main-menu__link' href="/book">Добавить книгу</a>
        </nav>

        <div class="user-block">
            <?php if(isset($_SESSION['user'])):?>
                <div class="user-block__name">
                    <?=$_SESSION['user']->firstname?>
                </div>
                <a href="/logout">Выход</a>
            <?php else:?>
                <a href="/auth">Авторизация</a> / 
                <a href="/registration">Регистрация</a>
            <?php endif?>
        </div>
    </header>

    <main>

    <h1><?= isset($title) ? $title : ''?></h1>