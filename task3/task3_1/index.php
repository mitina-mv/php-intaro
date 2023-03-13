<?php
declare(strict_types = 1);

// автозагрузчик от composer
require './../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/common-function.php";

use Task3\FileManager;
use Task3\RecordDateTrip;

$innerFileManager = new FileManager('./test/*.dat');
$outFileManager = new FileManager('./test/*.ans');

// обработка входных данных
for($i = 0; $i < $innerFileManager->getCountFiles(); ++$i)
{
    $innerContent = $innerFileManager->getContent($i);
    $outContent = $outFileManager->getContent($i);
    $innerResult = [];

    unset($innerContent[0]);

    foreach($innerContent as $str)
    {
        $innerResult[] = (new RecordDateTrip($str))->getSeconds();
    }

    if(count($innerResult) != count($outContent))
    {
        echo "Что-то пошло не так! Я не знаю, как здесь нарисовать табличку! О БОЖЕ! <br />";
    } 
    else 
    {
        $tableRows = [];
        foreach($innerResult as $key => $ans)
        {
            $tableRows[] = [
                ($ans == (trim($outContent[$key]) ?: '--')), // тут стравнение
                $key + 1,
                $outContent[$key] ?: "--", 
                $ans
            ];
        }

        // выводим результаты тестирования 
        echo "<h3>Тест #{$i}</h3>";
        echo testTableNew(
            ["Статус", "Номер строки", "Значение из файла", "Значение программы"],
            $tableRows
        );
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise 4</title>
    <link rel="stylesheet" href="/common/common-styles.css">
</head>
<body>