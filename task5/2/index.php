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
    $innerContentMass = $innerFileManager->getContent($i);
    $outContent = $outFileManager->getContent($i);
    $innerResult = [];

    $innerContent = [];
    // массив входных данных
    foreach($innerContentMass as $str)
    {
        $tmp = explode(' ', $str);
        $innerContent[$tmp[0]] = [
            'name' => $tmp[1],
            'leftKey' => $tmp[2],
            'rightKey' => $tmp[3]
        ];
    }

    $j = 1; // счетчик узлов - отражает заход
    $queue = array_keys($innerContent); // очередь из id - контроль остановки цикла
    $level = 0; // уровень - кол-во отрис. "-"
    $lastID = null;  // фикс. пред id - чтобы отличить лист от остальных 

    while($queue)
    {
        $node = [];
        $rightIndex = false; // если не лист, но уже пройден

        foreach($innerContent as $id => $arr)
        {
            if($j == $arr['leftKey'] || $j == $arr['rightKey'])
            {
                if($j == $arr['rightKey'] && $lastID != $id)
                {
                    $rightIndex = true;
                }
                
                $node = $arr;
                ++$j;

                break;
            }
        }

        // если это лист или уже пройденный родитель - избегаем повторного включения
        if($lastID == $id || $rightIndex)
        {
            --$level;
        } else { // если нашли этот уровень впервые
            $innerResult[] = str_repeat('-', $level) . $node['name'];
            ++$level;
            $lastID = $id;

            // удаляем из очереди
            unset($queue[array_search($id, $queue)]);
        }
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
    <title>5.2 - задание В</title>
    <link rel="stylesheet" href="/common/common-styles.css">
</head>
<body>