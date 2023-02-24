<?php
// это установка строгой типизации
declare(strict_types=1);

// подключаем файлики ставок и файл с общими функциями
require_once "./../../common/common-function.php";
require "./Bet.php";
require "./Player.php";
require "./Game.php";
require "./GameStorage.php";

// говорим, что используем эту всю красоту из неймспейса
use Ex1\GameStorage;
use Ex1\Bet;
use Ex1\Player;
use Ex1\Game;

// забираем пути файликов для проведения тестов по маске от текущего расположения ("./")
$pathInner = glob('./test/*.dat');
$pathOuter = glob('./test/*.ans');

$gameStorages = []; // хранилище хранилищ игр :)
$players = []; // хранилище игроков

// перебираем файлы входных данных
foreach($pathInner as $key => $path)
{
    $fileContent = file($path); // получаем контент из файлика в виде массива строк
    $bets = [];
    $i = 1;

    $gameStorage = new GameStorage(); // создаем хранилище игр для текущего файла входных данных

    // перебираем строки, создаем ставки
    for($i; $i <= $fileContent[0]; ++$i)
    {
        $betInfo = explode(" ", $fileContent[$i]); // разбиваем на массив по пробелу

        if(count($betInfo) == 3) // минимальная проверка на валидность результата
        {
            $bets[] = new Bet(
                (int)$betInfo[0], 
                (float)$betInfo[1], 
                trim($betInfo[2])
            );
        } else {
            throw new \Exception("Ошибка ввода данных для ставки");
        }
    }

    // создаем игрока со ставками
    $players[] = new Player($bets, "игрок {$key}");

    $gameCount = $fileContent[$i];

    // перебираем строки, создаем игры
    for($j = ($i + 1); $j <= ($i + $fileContent[$i]); ++$j)
    {
        $gameInfo = explode(" ", $fileContent[$j]);
        
        if(count($gameInfo) == 5)
        {            
            $gameStorage->setGame((new Game(
                (int) $gameInfo[0],
                (float) $gameInfo[1],
                (float) $gameInfo[2],
                (float) $gameInfo[3],
                trim($gameInfo[4])
            )));
        } else {
            throw new \Exception("Ошибка ввода данных для игры");
        }
    }

    // добавляем хранилище в хранилище хранилищ :)
    $gameStorages[] = $gameStorage;
}

// для генерации красивых таблиц
$table = '<table><tr><th>Статус</th><th>Текст</th><th>Значение из файла</th><th>Значение программы</th></tr>';

// перебираем пути файлов выходных данных, производим расчет баланса, заполняем таблицу
foreach($pathOuter as $key => $path)
{    
    $outResult = file($path)[0]; // читаем файлик
    // получаем и огругляем баланс
    $programmResult = round($players[$key]->getBalance($gameStorages[$key]));

    // добавляем данные в таблицу
    $table .= testTable(
        ($outResult == $programmResult), 
        $key + 1, 
        $outResult, 
        $programmResult
    );
}

$table .= '</table>';
echo $table; // вывод таблицы

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise 1</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>