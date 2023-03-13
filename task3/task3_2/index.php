<?php
declare(strict_types = 1);

// автозагрузчик от composer
require './../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/common-function.php";

for($i = 1; $i <= 6; ++$i)
{
    // получаем содержимое файлов xml
    $xmlprod = file_get_contents("./test/00{$i}_products.xml");
    $xmlres = file_get_contents("./test/00{$i}_result.xml");
    $xmlsect = file_get_contents("./test/00{$i}_sections.xml");

    $prodSections = [];
    $prods = [];

    $xmlIterator = new SimpleXMLIterator( $xmlprod );
    for($xmlIterator->rewind(); $xmlIterator->valid(); $xmlIterator->next()) 
    {
        // if($xmlIterator->hasChildren()) 
        // {
            $prodSections[(string)$xmlIterator->current()->Ид] = (array)$xmlIterator->current()->Разделы->ИдРаздела;
            $prods[(string)$xmlIterator->current()->Ид] = (array)$xmlIterator->current();
        // }
    }
    
    $xmlIterator = new SimpleXMLIterator( $xmlsect );
    for($xmlIterator->rewind(); $xmlIterator->valid(); $xmlIterator->next()) 
    {
        $sectionId = (string)$xmlIterator->current()->Ид;
        $prodsInSection = $xmlIterator->current()->addChild('Товары');
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
    echo $xmlsect->asXML();
}

$xmlstr = file_get_contents('./test/002_products.xml');
$document = new SimpleXMLElement($xmlstr);
/* имя корневого элемента */
// $name = $document->getName();
// print_r($name);
// echo "<pre>";
// print_r(xmlObjToArr($document));
// echo "</pre>";


// $fabric = (new NavigatorFabric())->setXml($xml);
// $converter = $fabric->makeConverter();
// $arrayRepresentationOfXml = $converter->toArray();