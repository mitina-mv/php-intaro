<?php
declare(strict_types = 1);

require_once "./../../common/common-function.php";

function getShortPath(array $graph, string $frNode, string $toNode)
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
    $nodes = array_unique($nodes); // удаляем дубли вершин

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

        // if(!$curNode) $curNode = $queue[0];
        p($curNode);

        // если расстояние до этой вершины бесконечно или она целевая, то выходим из while
        // бесконечное расстояние говорит о том, что в вершину нет пути
        // 
        if($dist[$curNode] == INF || $curNode == $toNode)
        {
            break;
        } 

        // отмечаем текущую вершину пройденной и удаляем из "очереди"
        $queue = array_diff($queue, [$curNode]);

        /* здесь идет пересчет меток - путей до вершин */
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

        // p($queue);

        // если у текущей вершины все смежные пройдены, то путь к искомой невозможен
        // $countPassedNodes = 0;
        // foreach($matrix[$curNode] as $nodeEdge)
        // {
        //     if(array_key_exists($nodeEdge["end"], $queue) === false)
        //     {
        //         ++$countPassedNodes;
        //     }
        // }

        // if(count($matrix[$curNode]) == $countPassedNodes) break;
    }

    $path = array();
    $curNode = $toNode;

    // обратным путем идем от конечной вершины к начальной
    // пока есть предществующие вершины
    // while ($previous[$curNode]) {
    //     array_unshift($path, $curNode); // добавляем текущий узел в путь (в начало массива)
    //     $curNode = $previous[$curNode]; // новым текущим узлом становится предществующий
    // }

    // // добавляем в начало пути вершину-источник
    // array_unshift($path, $curNode);
    
    return $dist[$toNode] != INF ? $dist[$toNode] : -1;
}

$graph_array = array(
    array("0", "1", 10),
    array("0", "2", 10),
    array("1", "2", 10),
    array("3", "4", 10),
    array("3", "5", 10),
    // array("0", "3", 110),
    array("4", "5", 10)
);

$path = getShortPath($graph_array, "0", "3");
echo "path is: ".$path;

$path = getShortPath($graph_array, "1", "4");
echo "path is: ".$path;
