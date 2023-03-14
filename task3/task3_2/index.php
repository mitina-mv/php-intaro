<?php
declare(strict_types = 1);

// автозагрузчик от composer
require './../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/common-function.php";

for($i = 1; $i <= 6; ++$i)
{
    // получаем содержимое файлов xml
    $xmlprod = file_get_contents("./test/00{$i}_products.xml");
    $xmlres = preg_replace(
        '/[\r\n\s]/', 
        "", 
        file_get_contents("./test/00{$i}_result.xml")
    );
    $xmlsect = file_get_contents("./test/00{$i}_sections.xml");

    $prodSections = [];
    $prods = [];

    $xmlIterator = new SimpleXMLIterator( $xmlprod );
    for($xmlIterator->rewind(); $xmlIterator->valid(); $xmlIterator->next()) 
    {
        $prodSections[(string)$xmlIterator->current()->Ид] = 
            (array)$xmlIterator->current()
                ->Разделы
                ->ИдРаздела;
        $prods[(string)$xmlIterator->current()->Ид] = (array)$xmlIterator->current();
    }

    $xmlProgResult = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><ЭлементыКаталога></ЭлементыКаталога>');
    $resSections = $xmlProgResult->addChild('Разделы');
    
    $xmlIterator = new SimpleXMLIterator( $xmlsect );
    for($xmlIterator->rewind(); $xmlIterator->valid(); $xmlIterator->next()) 
    {
        $curSection = $resSections->addChild('Раздел');

        $curSection->addChild('Ид', (string)$xmlIterator->current()->Ид);
        $curSection->addChild('Наименование', (string)$xmlIterator->current()->Наименование);

        $prodsInSection = $curSection->addChild('Товары');
        
        $sectionId = (string)$xmlIterator->current()->Ид;

        foreach($prodSections as $prodId => $arSectionId)
        {
            if(in_array($sectionId, $arSectionId))
            {
                $prod = $prodsInSection->addChild('Товар');
                $prod->addChild('Ид', $prodId);
                $prod->addChild('Наименование', $prods[$prodId]['Наименование']);
                $prod->addChild('Артикул', $prods[$prodId]['Артикул']);
            }
        }        
    }

    $programmResult = $xmlProgResult->asXML();
    $programmResult = preg_replace(
        '/[\r\n\s]/', 
        "", 
        $programmResult
    );

    $tableRows[] = [
        $programmResult == $xmlres,
        $programmResult,
        $xmlres
    ];

    // выводим результаты тестирования 
    echo "<h3>Тест #{$i}</h3>";
    echo testTableNew(
        ["Статус", "Значение программы", "Значение из файла"],
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
    <link rel="stylesheet" href="/common/common-styles.css">
</head>
<body>