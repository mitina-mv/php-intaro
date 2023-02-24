<?php
declare(strict_types = 1);

require_once $_SERVER['DOCUMENT_ROOT'] . "/common/common-function.php";

$innerContent = file('./test/test.dat');
$outerContent = [];

foreach($innerContent as $str)
{
    $newStr = preg_replace_callback("/['][\d]+[']/", function($matches){
        $num = trim($matches[0], "'");
        return "'" . (int)$num * 2 . "'";
    }, $str);

    echo "$str  ->  $newStr <br>";
}
