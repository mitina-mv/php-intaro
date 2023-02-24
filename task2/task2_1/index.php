<?php
declare(strict_types = 1);

require_once $_SERVER['DOCUMENT_ROOT'] . "/common/common-function.php";

$innerContent = file('./test/test.dat');
$outerContent = [];

foreach($innerContent as $str)
{
    $outerContent[] = preg_replace_callback(
        "/['][\d]+[']/", 
        function($matches)
        {
            $num = trim($matches[0], "'");
            return "'" . (int)$num * 2 . "'";
        }, 
        $str
    );
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TASK 2 - 1</title>
    <link rel="stylesheet" href="/common/common-styles.css">
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>№</th>
                <th>Входная строка</th>
                <th>Выходная строка</th>
            </tr>
        </thead>

        <tbody>            
            <?php foreach($outerContent as $key => $str): ?>
                <tr>
                    <td><?=$key + 1?></td>
                    <td><?=$innerContent[$key]?></td>
                    <td><?=$str?></td>
                </tr>
            <?php endforeach;?>
            <tr></tr>
        </tbody>
    </table>
</body>
</html>

