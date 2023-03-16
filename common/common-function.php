<?php

function p($array) 
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

// TODO удалить и перевести весь используемый код на функцию testTableNew()
function testTable($status, $num, $val1, $val2){
    $color = $status ? 'success' : 'error';
    $text = $status ? "Тест {$num} пройден <b>успешно</b>." : "Тест {$num} пройден <b>неудачно</b>.";

    return "<tr><td class='{$color}'></td><td>$text</td><td>$val1</td><td>$val2</td>";
}

function testTableNew (array $headTable, $rows)
{
    $table = '<table><tr>';

    // генерация шапки таблицы
    foreach($headTable as $th)
    {
        $table .= "<th>{$th}</th>";
    }
    $table .= '</tr>';

    // генерация содержимого
    foreach($rows as $row)
    {
        $table .= '<tr>';
        foreach($row as $cell)
        {
            // TODO ввести аргументы (классы и т.д.) для генерации более гибких таблиц
            if(is_bool($cell))
            {
                $color = $cell ? 'success' : 'error';
                $table .= "<td class='{$color}'></td>";
                continue;
            }

            $table .= "<td>{$cell}</td>";
        }
        $table .= '</tr>';
    }

    $table .= '</table>';

    return $table;
}
function getNextArrElement($array, $key) {
    return current(
        array_slice(
            $array, 
            array_search(
                $key, 
                array_keys($array)
            ) + 1, 
            1
        )
    );
 }

// функция получения GET-параметров из URL
function getUrlQuery($url, $key = null)
{
	$parts = parse_url($url);
     
	if (!empty($parts['query'])) 
    {
		parse_str($parts['query'], $query);

		if (is_null($key)) 
        {
			return $query;
		} 
        elseif (isset($query[$key]))
        {
			return $query[$key];
		}        
	}
 
	return false;
}
