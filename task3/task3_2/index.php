<?php
declare(strict_types = 1);

// автозагрузчик от composer
require './../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/common-function.php";

for($i = 1; $i <= 6; ++$i)
{
    $xmlprod = simplexml_load_file("./test/00{$i}_products.xml");
    $xmlsect = simplexml_load_file("./test/00{$i}_sections.xml");

    if($xmlsect->Раздел->count() == 1) {
        $arSections[] = $xmlsect->Раздел;
    } else {
        $arSections = ((array)$xmlsect)['Раздел'];
    }
    // $arSections;

    $prods = [];
    if($xmlprod->Товар->count() == 1) {
        $prods[] = $xmlprod->Товар;
    } else {
        $prods = ((array)$xmlprod)['Товар'];
    }

    foreach($arSections as &$section)
    {
        $prodsInSection = $section->addChild('Товары');

        foreach($prods as $prod)
        {
            $prodSectId = [];
            $prodSectId = array_merge($prodSectId, (array)$prod->Разделы->ИдРаздела);

            if(in_array($section->Ид, $prodSectId))
            {
                $prodSect = $prodsInSection->addChild('Товар');
                $prodSect->addChild('Ид', (string)$prod->Ид);
                $prodSect->addChild('Наименование', (string)$prod->Наименование);
                $prodSect->addChild('Артикул', (string)$prod->Артикул);
            }
        }
    }

    echo '<pre>';
    print_r($arSections);
    echo '</pre>';

    $xmlResult = new SimpleXMLElement('<?xml version="1.0"?><Разделы></Разделы>');
    arrayToXml($arSections, $xmlResult);
    $result = $xml_data->asXML('/file/path/name.xml');
    

    // получаем содержимое файлов xml
    // $xmlprod = file_get_contents("./test/00{$i}_products.xml");
    // $xmlres = file_get_contents("./test/00{$i}_result.xml");
    // $xmlsect = file_get_contents("./test/00{$i}_sections.xml");

    // $prodSections = [];
    // $prods = [];

    // $xmlIterator = new SimpleXMLIterator( $xmlprod );
    // for($xmlIterator->rewind(); $xmlIterator->valid(); $xmlIterator->next()) 
    // {
    //     // if($xmlIterator->hasChildren()) 
    //     // {
    //         $prodSections[(string)$xmlIterator->current()->Ид] = (array)$xmlIterator->current()->Разделы->ИдРаздела;
    //         $prods[(string)$xmlIterator->current()->Ид] = (array)$xmlIterator->current();
    //     // }
    // }
    
    // $xmlIterator = new SimpleXMLIterator( $xmlsect );
    // for($xmlIterator->rewind(); $xmlIterator->valid(); $xmlIterator->next()) 
    // {
    //     $sectionId = (string)$xmlIterator->current()->Ид;
    //     $prodsInSection = $xmlIterator->current()->addChild('Товары');
    //     foreach($prodSections as $prodId => $arSectionId)
    //     {
    //         if(in_array($sectionId, $arSectionId))
    //         {
    //             $prod = $prodsInSection->addChild('Товар');
    //             $prod->addChild('Ид', $prodId);
    //             $prod->addChild('Наименование', $prods[$prodId]['Наименование']);
    //             $prod->addChild('Артикул', $prods[$prodId]['Артикул']);
    //         }
    //     }        
    // }
    // echo $xmlsect->asXML();
}

// $xmlstr = file_get_contents('./test/002_products.xml');
// $document = new SimpleXMLElement($xmlstr);
/* имя корневого элемента */
// $name = $document->getName();
// print_r($name);
// echo "<pre>";
// print_r(xmlObjToArr($document));
// echo "</pre>";


// $fabric = (new NavigatorFabric())->setXml($xml);
// $converter = $fabric->makeConverter();
// $arrayRepresentationOfXml = $converter->toArray();