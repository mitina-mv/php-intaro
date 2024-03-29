<?php
declare(strict_types = 1);

require_once "./../../common/common-function.php";

function getShortPath(array $graph, string $frNode, string $toNode) : array
{
    $nodes = [];
    $matrix = [];

    foreach($graph as $edge)
    {
        array_push($nodes, $edge[0], $edge[1]); // добавляю концы ребер в массив вершин
        // составляем матрицу смежности (правильное название?)
        $matrix[$edge[0]][] = array("end" => $edge[1], "cost" => $edge[2]);
        $matrix[$edge[1]][] = array("end" => $edge[0], "cost" => $edge[2]);
    }

    // p($matrix);
    $nodes = array_values(array_unique($nodes)); // удаляем дубли вершин

    // перебираем вершины, чтобы установить пути и предыдущие вершины до них
    foreach ($nodes as $node) {
        $dist[$node] = INF; // по умолчанию путь равен бесконечности 
        $previous[$node] = NULL; // предыдущих вершин нет
    }

    // до начальной вершины путь ноль
    $dist[$frNode] = 0;

    // создаю очередь, чтобы удалять из нее вершины по мере прохождения
    $queue = $nodes;

    while(count($queue) > 0)
    {
        $min = INF; // минимум тоже бесконечный
        $curNode = null;
        
        // переираем непройденные вершины
        foreach ($queue as $node)
        {
            // если путь к этой вершине меньше минимума, то
            if ($dist[$node] < $min) 
            { 
                $min = $dist[$node]; // минимум обновляем
                $curNode = $node; // вершину запоминаем
            }
        }

        // если расстояние до этой вершины бесконечно или она целевая, то выходим из while
        // бесконечное расстояние говорит о том, что в вершину нет пути
        if($dist[$curNode] == INF || $curNode == $toNode || $curNode == null)
        {
            break;
        }

        $queue = array_values(array_diff($queue, [$curNode]));

        // отмечаем текущую вершину пройденной и удаляем из "очереди"

        /* ЗДЕСЬ ИДЕТ ПЕРЕСЧЕТ МЕТОК - ПУТЕЙ ДО ВЕРШИН */

        // если у данной вершины есть смежные вершины
        if ($matrix[$curNode]) {
            // перебираем смежные вершины
            foreach ($matrix[$curNode] as $nodeEdge) 
            {
                $newDist = $dist[$curNode] + $nodeEdge["cost"]; // считаем путь от найденной вершины к этой

                // если новый путь до смежной вершины короче того, что был высчитан ранее
                if ($newDist < $dist[$nodeEdge["end"]]) 
                {
                    $dist[$nodeEdge["end"]] = $newDist; // обновляем метку пути к конечной вершине ребра
                    $previous[$nodeEdge["end"]] = $curNode; // записываем текущую вершину в предшественники
                }
            }
        }
    }

    $result = [
        'path' => [],
        'dist' => $dist[$toNode] != INF ? $dist[$toNode] : -1
    ];

    // обратным путем идем от конечной вершины к начальной
    if($dist[$toNode] != INF)
    {
        $curNode = $toNode;
        // пока есть предществующие вершины
        while (isset($previous[$curNode])) 
        {
            array_unshift($result['path'], $curNode); // добавляем текущий узел в путь (в начало массива)
            $curNode = $previous[$curNode]; // новым текущим узлом становится предществующий
        }
    
        // добавляем в начало пути вершину-источник
        array_unshift($result['path'], $curNode);
    }

    return $result;
}

function getEdge(array $graph, string $frNode, string $toNode) : int
{
    foreach($graph as $key => $edge)
    {
        if($edge[0] == $frNode && $edge[1] == $toNode)
        {
            return $key;
        }
    }

    return -1;
}

// РЕАЛИЗАЦИЯ ТЕСТИРОВАНИЯ

// получаем пути к файликам по маске
$pathInner = glob('./test/*.dat');
$pathOuter = glob('./test/*.ans');

// перебираем i от 0 до количесва элементов массива $pathInner
for($i = 0; $i < count($pathInner); ++$i)
{
    // считываем значения из файла по его пути
    $innerData = file($pathInner[$i]);
    $settingsGraph = explode(' ', $innerData[0]);

    // массив ребер графа
    $graph = [];

    // составляем граф из исходных данных
    for($j = 1; $j <= $settingsGraph[1]; ++$j)
    {
        $edge = explode(" ", $innerData[$j]);

        $fr = trim($edge[0]);
        $to = trim($edge[1]);

        $graph[] = [$fr, $to, (int)$edge[2]];
    }

    // определяем, все ли вершины хотя бы раз участвуют в графе
    $additionalNodes = array_values(  // переиндексация => 0, 1, 2... 
        array_unique(       // удаляем дубли в массиве вершин
            array_merge(    // объединяем массивы исходных и конечных вершин
                array_column($graph, 0),  // исходные вершины
                array_column($graph, 1)   // конечные вершины
            )
        )
    );

    // если количество вершин в графе не равно указанному
    if(count($additionalNodes) != $settingsGraph[0])
    {
        // генерируем новые вершины к недостающим
        for($j = 0; $j < $settingsGraph[0]; ++$j)
        {
            // замыкаем вершину саму на себя
            if(!in_array($j, $additionalNodes)){
                $graph[] = [(string)$j, (string)$j, 0];
            }
        }
    }

    $indexCountRequest = $settingsGraph[1] + 1;
    $countRequest = $innerData[$indexCountRequest];
    $programmAnswers = [];

    // получаем и выполняем работы над графом
    for($j = 1; $j <= $countRequest; ++$j)
    {
        $request = explode(" ", $innerData[$j + $indexCountRequest]);

        foreach($request as &$rq)
        {
            $rq = trim($rq);
        }
        
        // какое из выражений будет истинным
        switch (true) {
            case ($request[2] == '-1'):
                // получаем индекс удаляемого ребра
                $index = getEdge($graph, $request[0], $request[1]);

                if($index != -1)
                {
                    unset($graph[$index]);
                }
                break;

            case ($request[2] == '?'):
                // получаем путь 
                $programmAnswers[] = getShortPath($graph, $request[0], $request[1]);
                break;

            case is_int((int)$request[2]):
                // получаем индекс ребра, которое модифицируем
                $index = getEdge($graph, $request[0], $request[1]);

                // если есть такое ребро - меняем
                if($index != -1)
                {
                    $graph[$index][2] = (int)$request[2];
                }
                // если нет - создаем новое ребро
                else 
                {
                    $graph[] = $request;
                }
                break;
            
            default:
                throw new Exception("О БОГИ, что послали вы мне?!");
                break;
        }

    }

    $outerData = file($pathOuter[$i]);
    $tableRows = [];

    // генерируем содержимое для таблицы теста
    foreach($programmAnswers as $key => $ans)
    {
        $tableRows[] = [
            ($ans['dist'] == (trim($outerData[$key]) ?: '--')), // тут стравнение
            $key + 1,
            $outerData[$key] ?: "--", 
            $ans['dist'],
            ($ans['dist'] != -1) ? implode(' -> ', $ans['path']) : 'пути нетю'
        ];
    }

    // выводим результаты тестирования 
    echo "<h3>Тест #{$i}</h3>";
    echo testTableNew(
        ["Статус", "Номер строки", "Значение из файла", "Значение программы", "Найденный путь"],
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
    <title>Exercise 4</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

