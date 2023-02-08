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
        while ($previous[$curNode]) {
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

    for($j = 1; $j <= $settingsGraph[1]; ++$j)
    {
        $edge = explode(" ", $innerData[$j]);

        $fr = trim($edge[0]);
        $to = trim($edge[1]);

        $graph[] = [$fr, $to, (int)$edge[2]];
    }

    $additionalNodes = array_values(
        array_unique(
            array_merge(
                array_column($graph, 0),
                array_column($graph, 1)
            )
        )
    );

    if(count($additionalNodes) != $settingsGraph[0])
    {
        for($j = 0; $j < $settingsGraph[0]; ++$j)
        {
            if(!in_array($j, $additionalNodes)){
                $graph[] = [(string)$j, (string)$j, 0];
            }
        }
    }
        p($graph);

    $indexCountRequest = $settingsGraph[1] + 1;
    $countRequest = $innerData[$indexCountRequest];
    $programmAnswers = [];

    for($j = 1; $j <= $countRequest; ++$j)
    {
        $request = explode(" ", $innerData[$j + $indexCountRequest]);

        foreach($request as &$rq)
        {
            $rq = trim($rq);
        }
        
        switch (true) {
            case ($request[2] == '-1'):
                $index = getEdge($graph, $request[0], $request[1]);

                if($index != -1)
                {
                    unset($graph[$index]);
                }
                break;

            case ($request[2] == '?'):
                $programmAnswers[] = getShortPath($graph, $request[0], $request[1]);
                break;

            case is_int((int)$request[2]):
                $index = getEdge($graph, $request[0], $request[1]);

                if($index != -1)
                {
                    $graph[$index][2] = (int)$request[2];
                }
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
            ($ans == (trim($outerData[$key]) ?: '--')), // тут стравнение
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

$graph_array = array(
    // array("0", "1", 10),
    array("0", "2", 10),
    array("1", "2", 10),
    array("3", "4", 10),
    array("3", "5", 10),
    array("8", "8", 0),
    // array("0", "3", 110),
    array("4", "5", 10)
);

$res = getShortPath($graph_array, "0", "3");
p($res);

$res = getShortPath($graph_array, "1", "8");
p($res);

