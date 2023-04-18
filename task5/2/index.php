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

    $j = 1;
    $queue = array_keys($innerContent);
    $level = 0;
    $lastID = null;

    // print_r($queue);
    // print_r(array_search(11, $queue));

    while($queue)
    {
        $node = [];
        $rightIndex = false;
        // print_r($queue);

        foreach($innerContent as $id => $arr)
        {
            if($j == $arr['leftKey'] || $j == $arr['rightKey'])
            {
                $node = $arr;
                ++$j;

                // if($j == $arr['rightKey'] && $lastID != $id)
                // {
                //     $rightIndex = true;
                // }

                break;
            }
        }

        // echo $level . "<br>";
        // print_r($node);
        // print_r($queue);

        if($lastID == $id || $rightIndex)
        {
            --$level;
        } else {
            $innerResult[] = str_repeat('-', $level) . ' ' . $node['name'];
            ++$level;
            $lastID = $id;

            unset($queue[array_search($id, $queue)]);
            // unset($innerContent[$id]);
        }
    }


    print_r($innerResult);

    /* 
    создаем счетчик = 1 фиксируем в нем сдвиг
    ищем по массиву входа в [1] совпадение по счетчику
    если в [1] совпадение есть 
        фиксируем этот узел в уровне, увеличиваем уровень, фикс текущий ид узла
        в переменую ласт ид
        и снова
    иначе
        ищем по [2] совпадение с счетчиков
        если нашли
            фиксируем узел
            сравниваем текущий ид и ласт ид
            если равны - это лист, уменьшаем уровень
            иначе - --
        иначе
            конец
    
    */
}