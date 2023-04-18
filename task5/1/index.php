<?php
declare(strict_types = 1);

// автозагрузчик от composer
require './../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/common-function.php";

use Task5\FileManager;

echo "<pre>";

$innerFileManager = new FileManager('./test/*.dat');
$outFileManager = new FileManager('./test/*.ans');

// обработка входных данных
for($i = 0; $i < $innerFileManager->getCountFiles(); ++$i)
{
    $innerContent = $innerFileManager->getContent($i);
    $outContent = $outFileManager->getContent($i);
    $innerResult = [];
    $progResult = [];

    foreach($innerContent as $str)
    {
        // делим строку по 8-пробельному символу
        $tmp = preg_split(
            '/\s{8}/', 
            trim($str)
        );
        
        // модифицируем запись показа, если есть
        if($progResult[$tmp[0]])
        {
            $progResult[$tmp[0]]['count'] += 1;
            $progResult[$tmp[0]]['date'] = $tmp[1];
        } 
        // иначе создаем запись показа
        else { 
            $progResult[$tmp[0]] = [
                'count' => 1,
                'date' => $tmp[1]
            ];
        }
    }

    // создание строки ответа
    foreach($progResult as $key => $arr)
    {
        $innerResult[] = <<<EOT
        {$arr["count"]} {$key} {$arr["date"]}
        EOT;
    }

    // данные для отрисовки в таблице
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
        [
            "Статус", 
            "Номер строки", 
            "Значение из файла", 
            "Значение программы"
        ],
        $tableRows
    );
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