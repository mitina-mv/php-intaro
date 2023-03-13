<?php
declare(strict_types = 1);

// автозагрузчик от composer
require './../vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/common-function.php";

$xmlstr = file_get_contents('./test/002_products.xml');
$document = new SimpleXMLElement($xmlstr);
/* имя корневого элемента */
// $name = $document->getName();
// print_r($name);
echo "<pre>";
print_r(xmlObjToArr($document));
echo "</pre>";


// $fabric = (new NavigatorFabric())->setXml($xml);
// $converter = $fabric->makeConverter();
// $arrayRepresentationOfXml = $converter->toArray();