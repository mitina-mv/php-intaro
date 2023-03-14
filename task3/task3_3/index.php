<?php
declare(strict_types = 1);

// автозагрузчик от composer
require './../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/common-function.php";
require_once "colors.php";

use Task3\FileManager;

$innerFileManager = new FileManager('./test/*.dat');

for($i = 0; $i < $innerFileManager->getCountFiles(); ++$i)
// for($i = 0; $i < 1; ++$i)
{
    $content = $innerFileManager->getContent($i);
    $positions = [];

    for($j = 1; $j <= $content[0]; ++$j)
    {
        $positions[] = trim($content[$j]);
    }

    $usersActions = array_combine(
        $positions,
        array_fill(0, count($positions), 0)
    );

    $sumActions = 0;

    for($k = ($j + 1); $k <= ($j + $content[$j]); ++$k)
    {
        $tmp = explode(" ", $content[$k]);
        ++$usersActions[trim($tmp[1])];
        ++$sumActions;
    }
    p($usersActions);

    // Создать изображение с указанными размерами
   $image = imageCreate(600, 600);
   $bgcolor = imagecolorallocate($image, 255, 255, 255); // задает фон

    // полная высота 600. делаем по 10 сверху-снизу и получаем 100% = 580
    // аналогично для ширины
    $lastY = 10;
    $color = [];
    foreach($usersActions as $pos => $num)
    {
        $color = constant('COLOR_' . $pos);
        $c = imagecolorallocate($image, $color[0], $color[1], $color[2]);

        $height = 580 * ($num / $sumActions); // высота полигона

        // TODO заменить прямоугольник на трапецию (?)
        imageFilledRectangle($image, 10, $lastY, 590, (int)$height + $lastY, $c);
        
        $lastY += (int)$height;
    }



    // TODO сделать наложение png с треугольником
   /* $color1 = imagecolorallocate($image, COLOR_SEARCH[0], COLOR_SEARCH[1], COLOR_SEARCH[2]);

   
   imagefilledpolygon($image, [
        10, 10, 
        20, 50, 
        560, 50,
        580, 10
    ], 4, $color1); */

   imagepng($image, './image' . $i . '.png');

}