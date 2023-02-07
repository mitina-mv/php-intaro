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

    foreach($innerData as $key => $line)
    {
        if($line == "::")
        {
            $programmAnswers[] = '0000:0000:0000:0000:0000:0000:0000:0000';
            continue;
        }

        $units = explode(":", trim($line));

        p($line);
        p($units);

        if(count($units) < 8)
        {
            foreach($units as $key => $unit)
            {
                if(empty($unit) && $unit != '0')
                {
                    $leftSide = array_slice($units, 0, $key);
                    $rigthSide = array_slice($units, $key + 1);

                    if(empty($rigthSide[0]))
                    {
                        $rigthSide[0] = '0';
                    }

                    $units = array_merge(
                        $leftSide, 
                        array_fill(0, (9 - count($units)), '0'),
                        $rigthSide
                    );

                    break;
                }
            }
        }
        
        foreach($units as &$unit)
        {
            if(strlen($unit) == 4) continue;
            
            while(strlen($unit) < 4)
            {
                $unit = '0' . $unit;
            }
        }
        
        $programmAnswers[] = implode(':', $units);
        
    }
}