<?php

require_once "./../../common/common-function.php";

require_once './src/DateField.php';
require_once './src/PhoneField.php';
require_once './src/StringField.php';
require_once './src/IntField.php';
require_once './src/EmailField.php';
require_once './src/Field.php';

use Ex3\DateField;
use Ex3\PhoneField;
use Ex3\StringField;
use Ex3\EmailField;
use Ex3\IntField;

// получаем пути к файликам по маске
$pathInner = glob('./test/*.dat');
$pathOuter = glob('./test/*.ans');

// перебираем i от 0 до количесва элементов массива $pathInner
for($i = 0; $i < count($pathInner); ++$i)
{
    // считываем значения из файла по его пути
    $innerData = file($pathInner[$i]);
    $programmAnswers = [];

    foreach($innerData as $data)
    {
        // режем строку. 0 = < - не берем в расчет
        // получаем позицию (> - 1) = координата конца содержимого поля
        $rowValue = substr($data, 1, strpos($data, ">") - 1);

        // получаем все остальные данные по строке для создания объекта поля
        $rowData = explode(
            " ", 
            substr($data, strpos($data, ">") + 2) // подстрока от >
        );

        // по типу опеределяем, какое поле создать 
        switch( trim($rowData[0]) )
        {
            case 'S':
                $field = new StringField(
                    $rowValue,
                    $rowData[0],
                    $rowData[1],
                    $rowData[2]
                );
                break;

            case 'D':
                $field = new DateField(
                    $rowValue,
                    $rowData[0]
                );
                break;

            case 'N':
                $field = new IntField(
                    $rowValue,
                    $rowData[0],
                    $rowData[1],
                    $rowData[2]
                );
                break;
            case 'P':
                $field = new PhoneField(
                    $rowValue,
                    $rowData[0]
                );
                break;
            case "E":
                $field = new EmailField(
                    $rowValue,
                    $rowData[0]
                );
                break;
            default: // если ничего не подошло
                throw new \Exception("Ошибка: неверный тип вадидации {$rowData[0]}");
                break;
        }
        
        // фиксируем результат
        $programmAnswers[] = $field->isValid();
    }

    // получаем массив выходных данных
    $outerData = file($pathOuter[$i]);

    // генерация таблицы
    $table = "<h3>Тест {$i}</h3><table><tr><th>Статус</th><th>Текст</th><th>Значение из файла</th><th>Значение программы</th></tr>";

    foreach($programmAnswers as $key => $ans)
    {
        $table .= testTable(
            ($ans == (trim($outerData[$key]) ?: '--')), 
            $key + 1, 
            $outerData[$key] ?: "--", 
            $ans
        );
    }

    // выводим таблицу по этому тесту
    $table .= '</table>';
    echo $table;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise 3</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

