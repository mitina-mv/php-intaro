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


// источник https://www.php.net/manual/ru/book.simplexml.php#108688
// xaviered at gmail dot com
function xmlObjToArr($obj) 
{
    $namespace = $obj->getDocNamespaces(true);
    $namespace[NULL] = NULL;   

    $children = array();
    $attributes = array();
    $name = strtolower((string)$obj->getName());    

    $text = trim((string)$obj);
    if( strlen($text) <= 0 ) {
        $text = NULL;
    }

    // get info for all namespaces
    if(is_object($obj)) {
        foreach( $namespace as $ns=>$nsUrl ) {
            // atributes
            $objAttributes = $obj->attributes($ns, true);

            foreach( $objAttributes as $attributeName => $attributeValue ) 
            {
                $attribName = strtolower(trim((string)$attributeName));
                $attribVal = trim((string)$attributeValue);
                if (!empty($ns)) {
                    $attribName = $ns . ':' . $attribName;
                }
                $attributes[$attribName] = $attribVal;
            }

            // children
            $objChildren = $obj->children($ns, true);
            foreach( $objChildren as $childName=>$child )
            {
                $childName = strtolower((string)$childName);
                if( !empty($ns) ) {
                    $childName = $ns.':'.$childName;
                }

                $children[$childName][] = xmlObjToArr($child);
            }
        }
    }

    return array(
        'name'=>$name,
        'text'=>$text,
        'attributes'=>$attributes,
        'children'=>$children
    );
}

// function defination to convert array to xml
function arrayToXml( $data, &$xml_data ) {
    foreach( $data as $key => $value ) {
        if( is_array($value) ) {
            if( is_numeric($key) ){
                $key = 'item'.$key; //dealing with <0/>..<n/> issues
            }
            $subnode = $xml_data->addChild($key);
            arrayToXml($value, $subnode);
        } else {
            $xml_data->addChild("$key",htmlspecialchars("$value"));
        }
     }
}