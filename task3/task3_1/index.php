<?php
declare(strict_types = 1);

// автозагрузчик от composer
require './../vendor/autoload.php';

use Task3\FileManager;
use Task3\RecordDateTrip;

$innerFileManager = new FileManager('./test/*.dat');
$outerFileManager = new FileManager('./test/*.ans');

// обработка входных данных
for($i = 0; $i < $innerFileManager->getCountFiles(); ++$i)
{
    $innerContent = $innerFileManager->getContent($i);
    $innerResult = [];

    unset($innerContent[0]);

    foreach($innerContent as $str)
    {
        $innerResult[] = (new RecordDateTrip($str))->getSeconds();
    }
}


