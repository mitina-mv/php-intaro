<?php
declare(strict_types=1);

require_once "./../../common/common-function.php";
require "./Bet.php";
require "./Player.php";
require "./Game.php";
require "./GameStorage.php";

use Ex1\GameStorage;
use Ex1\Bet;
use Ex1\Player;
use Ex1\Game;

$pathInner = glob('./test/*.dat');
$pathOuter = glob('./test/*.ans');

$gameStorages = [];
$players = [];

foreach($pathInner as $key => $path)
{
    $fileContent = file($path);
    $bets = [];
    $i = 1;

    $gameStorage = new GameStorage();

    for($i; $i <= $fileContent[0]; ++$i)
    {
        $betInfo = explode(" ", $fileContent[$i]);

        if(count($betInfo) == 3)
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

    $players[] = new Player($bets, "игрок {$key}");

    $gameCount = $fileContent[$i];

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

    $gameStorages[] = $gameStorage;
}

$table = '<table><tr><th>Статус</th><th>Текст</th><th>Значение из файла</th><th>Значение программы</th></tr>';

foreach($pathOuter as $key => $path)
{
    $outResult = file($path)[0];
    $programmResult = round($players[$key]->getBalance($gameStorages[$key]));

    $table .= testTable(
        ($outResult == $programmResult), 
        $key + 1, 
        $outResult, 
        $programmResult
    );
}

$table .= '</table>';
echo $table;

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