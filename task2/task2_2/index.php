<?php
declare(strict_types = 1);

// подключение общих функций
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/common-function.php";

// файл-источнк строк
$innerContent = file('./test/test.dat');
// хранилище выходных данных
$outerContent = [];
$outerContentRegular = [];

foreach($innerContent as $url)
{
    // проверка на соотвествие маске
    if (!preg_match(
        "/http:\/\/asozd\.duma\.gov\.ru\/main\.nsf\/\(Spravka\)\?OpenAgent&RN=[\d-]+&[\d]+/", 
        $url
        )
    ) 
    {
        $outerContent[] = 'Неверный формат';
        $outerContentRegular[] = 'Неверный формат';
        continue;
    }

    // способ 1 - забираем из url параметр RN и создаем новую строку
    // соотвествие URL не обязательно, но необходим параметр RN
    // функция getUrlQuery в файле общих функций
    $paramRN = getUrlQuery($url, 'RN');
    $outerContent[] = "http://sozd.parlament.gov.ru/bill/" . $paramRN;

    // способ 2 - решение прямо по заданию "с помощью регулярного выражения"
    // сначала меняем начало ссылки до номера приказа
    $newUrl = preg_replace(
        '/http:\/\/asozd\.duma\.gov\.ru\/main\.nsf\/\(Spravka\)\?OpenAgent&RN=/',
        'http://sozd.parlament.gov.ru/bill/',
        $url
    );

    // потом меняем конец ссылки на пустоту, потому что &<целое число> не используется
    $outerContentRegular[] = preg_replace(
        '/&[\d]+/',
        '',
        $newUrl
    );
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TASK 2 - 2</title>
    <link rel="stylesheet" href="/common/common-styles.css">
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>№</th>
                <th>Входная строка</th>
                <th>Выходная строка</th>
                <th>Выходная строка (от регулярки)</th>
            </tr>
        </thead>

        <tbody>            
            <?php foreach($outerContent as $key => $str): ?>
                <tr>
                    <td><?=$key + 1?></td>
                    <td><?=$innerContent[$key]?></td>
                    <td><?=$str?></td>
                    <td><?=$outerContentRegular[$key]?></td>
                </tr>
            <?php endforeach;?>
            <tr></tr>
        </tbody>
    </table>
</body>
</html>