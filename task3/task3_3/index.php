<?php
declare(strict_types = 1);

// автозагрузчик от composer
require './../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/common-function.php";
require_once "colors.php";

use Task3\FileManager;

$innerFileManager = new FileManager('./test/*.dat');
$allUserActions = [];

for($i = 0; $i < $innerFileManager->getCountFiles(); ++$i)
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

    $allUserActions[] = $usersActions;

    // Создать изображение с указанными размерами
   $image = imagecreate(600, 600);
   $bgcolor = imagecolorallocate($image, 255, 255, 255); // задает фон

    // полная высота 600. делаем по 10 сверху-снизу и получаем 100% = 580
    // аналогично для ширины
    $lastY = 10;

    foreach($usersActions as $pos => $num)
    {
        $color = constant('COLOR_' . $pos);
        $c = imagecolorallocate($image, $color[0], $color[1], $color[2]);

        $height = round(580 * ($num / $sumActions)); // высота полигона

        // TODO заменить прямоугольник на трапецию (?)
        imageFilledRectangle($image, 10, $lastY, 590, (int)$height + $lastY, $c);
        
        $lastY += (int)$height;
    }

    // TODO вместо наложения картинки сделать нормальную трапецию
    imagejpeg($image, './tmp' . ($i) . '.jpeg');

    $newImage = imageCreateFromJpeg('./tmp' . ($i) . '.jpeg');
    $crutch = imageCreateFromPng('./crutch.png');
    imageSaveAlpha($crutch, true);
    imagecopy($newImage, $crutch, 0, 0, 0, 0, 600, 600);
    imagepng($newImage, './00' . ($i + 1) . '.png');

    // удаление временной tmp файлика
    unlink('./tmp' . ($i) . '.jpeg');
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
    <table>
        <thead>
            <tr>
                <th>Номер</th>
                <th>Картинка из программы</th>
                <th>Картинка для проверки</th>
                <th>Массив для проверки</th>
            </tr>
        </thead>

        <tbody>
            <?php
                $progPicturesPath = glob('./00*.png');
                $outPicturesPath = glob('./test/*.png');
            ?>

            <?php foreach($progPicturesPath as $key => $pic):?>
                <tr>
                    <td><?= $key + 1?></td>
                    <td>
                        <img src="<?= $pic?>" alt="Картинка из программы" width="600">
                    </td>
                    <td>
                        <img src="<?= $outPicturesPath[$key]?>" alt="Картинка для проверки" width="600">
                    </td>
                    <td>
                        <pre>
                            <?php print_r($allUserActions[$key]);?>
                        </pre>
                    </td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>