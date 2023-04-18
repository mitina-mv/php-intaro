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
    $summ = 0;
    $innerContent = [];

    foreach($innerContentMass as $str)
    {
        $innerContent[] = $tmp = explode(' ', $str);
        $summ += $tmp[1];
    }

    // echo $summ . '<br>';

    foreach($innerContent as $arr)
    {
        $innerResult[] = round($arr[1] / $summ, 6);
    }

    print_r($innerResult);
}