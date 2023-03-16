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

    // получаем позиции для отрисовки
    for($j = 1; $j <= $content[0]; ++$j)
    {
        $positions[] = trim($content[$j]);
    }

    // массив подсчета количества действий по разным позициям
    $usersActions = array_combine(
        $positions,
        array_fill(0, count($positions), 0)
    );

    for($k = ($j + 1); $k <= ($j + $content[$j]); ++$k)
    {
        $tmp = explode(" ", $content[$k]);
        ++$usersActions[trim($tmp[1])];
    }

    $lastKey = array_key_last($usersActions);
    $arPicturePart = [];

    foreach($usersActions as $key => $act)
    {
        if($key == $lastKey)
        {
            $arPicturePart[$key] = $act;
        } else {
            $arPicturePart[$key] = $act - getNextArrElement($usersActions, $key);
        }
    }

    // фиксируется для проверки правильности отрисовки в итоговой таблице
    $allUserActions[] = $usersActions;
    // особенность задачи - 100% представляют собой число из первого действия
    $countUser = array_shift($usersActions);

    unset($usersActions);

    // Создать изображение с указанными размерами
   $image = imagecreate(600, 600);
   $bgcolor = imagecolorallocate($image, 255, 255, 255); // задает фон

    // полная высота 600. делаем по 10 сверху-снизу и получаем 100% = 580
    // высота одной линии картики
    $heightUnitPicture = round(580 / $countUser);

    // фиксируем сдвиг, чтобы рисовать линии друг под другом
    // точка (0, 0) в верхнем левом углу
    $lastY = 10;

    foreach($arPicturePart as $pos => $num)
    {
        // забираем цвет из константы с соотв. названием
        $color = constant('COLOR_' . $pos);
        $c = imagecolorallocate($image, $color[0], $color[1], $color[2]);

        $height = $heightUnitPicture * $num; // высота полигона

        // TODO заменить прямоугольник на трапецию (?)
        imageFilledRectangle($image, 10, $lastY, 590, (int)$height + $lastY, $c);
        
        // запоминаем сдвиг по У для следующей полосы
        $lastY += (int)$height;
    }

    // TODO вместо наложения картинки сделать нормальные трапеции
    // сохраняем временный jpeg
    imagejpeg($image, './tmp' . ($i) . '.jpeg');

    // открываем только сохраненный jpeg
    $newImage = imageCreateFromJpeg('./tmp' . ($i) . '.jpeg');
    // открываем файлик с треугольником с прозрачностью
    $crutch = imageCreateFromPng('./crutch.png');
    imageSaveAlpha($crutch, true);
    // накладываем триугольник поверх jpeg и сохраняем в png
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