<?php

function p($array) 
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

function testTable($status, $num, $val1, $val2){
    $color = $status ? 'success' : 'error';
    $text = $status ? "Тест {$num} пройден <b>успешно</b>." : "Тест {$num} пройден <b>неудачно</b>.";

    return "<tr><td class='{$color}'></td><td>$text</td><td>$val1</td><td>$val2</td>";
}