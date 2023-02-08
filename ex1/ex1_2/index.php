<?php
declare(strict_types=1);

require_once('./../../common/common-function.php');

// забираем пути файликов для проведения тестов по маске от текущего расположения ("./")
$pathInner = glob('./test/*.dat');
$pathOuter = glob('./test/*.ans');

// перебираем i от 0 до количесва элементов массива $pathInner
for($i = 0; $i < count($pathInner); ++$i)
{
    // считываем значения из файла по его пути
    $innerData = file($pathInner[$i]);
    $programmAnswers = [];

    // основной алгоритм
    foreach($innerData as $key => $line)
    {
        // разбиваем строку по : на массив
        $units = explode(":", trim($line));

        // если массив не из 8 частей, значит, в ней есть сокращения
        if(count($units) < 8)
        {
            // ищем ключ первого пустого значения - это результат работы разбиения ::
            $keyEmptyUnit = array_search('', $units);

            // если ключ найден
            if($keyEmptyUnit !== false)
            {
                // получаем левую и правую часть, чтобы не потерять их при добавлении в массив
                $leftSide = array_slice($units, 0, $keyEmptyUnit);
                $rigthSide = array_slice($units, $keyEmptyUnit + 1);

                // случай, когда :: стоит в конце строки
                if(empty($rigthSide[0]))
                {
                    $rigthSide[0] = '0';
                }

                // собираем новый массив соед. левой, правой части и сгенерированной недостающей
                $units = array_merge(
                    $leftSide, 
                    array_fill(0, (9 - count($units)), '0'),
                    $rigthSide
                );
            }
            // частей не 8, но ключ не найден - ошибочка :(
            else 
            {
                throw new \Exception('Ошибка ');
            }
        }
        
        // перебираем массив частей строки
        // unit - это ссылка соотв. на элемент в массиве. чтобы добалять изменения в $units
        foreach($units as &$unit)
        {
            // если длина строки = 4 - часть полная
            if(strlen($unit) == 4) continue;
            
            // добавляем в начало строки недостающие 0
            while(strlen($unit) < 4)
            {
                $unit = '0' . $unit;
            }
        }
        
        // объединяем строку обратно
        $programmAnswers[] = implode(':', $units);
        
    }

    $outerData = file($pathOuter[$i]);
    $tableRows = [];

    // генерируем содержимое для таблицы теста
    foreach($programmAnswers as $key => $ans)
    {
        $tableRows[] = [
            ($ans == (trim($outerData[$key]) ?: '--')), // тут стравнение
            $key + 1, 
            $innerData[$key],
            $outerData[$key] ?: "--", 
            $ans
        ];
    }

    // выводим результаты тестирования 
    echo "<h3>Тест #{$i}</h3>";
    echo testTableNew(
        ["Статус", "Номер строки", "Исходная строка","Значение для проверки", "Значение программы"],
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
    <title>Exercise 2</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>