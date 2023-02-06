<?php

require_once "./../../common/common-function.php";

require_once './src/DateField.php';
require_once './src/PhoneField.php';
require_once './src/StringField.php';
require_once './src/IntField.php';
require_once './src/EmailField.php';
require_once './src/Field.php';

use Ex2\DateField;
use Ex2\PhoneField;
use Ex2\StringField;
use Ex2\EmailField;
use Ex2\IntField;

$pathInner = glob('./test/*.dat');
$pathOuter = glob('./test/*.ans');

for($i = 0; $i < count($pathInner); ++$i)
{
    $innerData = file($pathInner[$i]);
    $programmAnswers = [];

    foreach($innerData as $data)
    {
        $rowValue = substr($data, 1, strpos($data, ">") - 1);
        $rowData = explode(
            " ", 
            substr($data, strpos($data, ">") + 2)
        );

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
            default:
                throw new \Exception("Ошибка: неверный тип вадидации {$rowData[0]}");
                break;
        }
        
        $programmAnswers[] = $field->isValid();
    }

    $outerData = file($pathOuter[$i]);

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
    <title>Exercise 1</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

