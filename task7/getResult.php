<?php
include_once('./env.php'); // TODO .env не получилось подключить

$json_str = file_get_contents('php://input'); 
$data = json_decode($json_str, true);

// запрос координат по адресу
$url = "https://geocode-maps.yandex.ru/1.x/?apikey=" . API_KEY ."&geocode=" . urlencode("{$data['toun']},{$data['address']}") . "&format=json";
$response = file_get_contents($url);
$coordsData = json_decode($response, false);

// получение координат из ответа
$coordinates = explode(' ', 
    $coordsData->response
        ->GeoObjectCollection
        ->featureMember[0]
        ->GeoObject->Point->pos
);

// ищем метро
$url = "https://geocode-maps.yandex.ru/1.x/?apikey=" . API_KEY ."&geocode={$coordinates[0]},{$coordinates[1]}&kind=metro&format=json";
$response = file_get_contents($url);
$metroData = json_decode($response, false);

$componentsResponse = $metroData->response
    ->GeoObjectCollection
    ->featureMember[0]
    ->GeoObject
    ->metaDataProperty
    ->GeocoderMetaData
    ->Address
    ->Components;

$count = count($componentsResponse);

echo json_encode([
    'line' => $componentsResponse[$count - 2]->name,
    'station' =>$componentsResponse[$count - 1]->name
]);