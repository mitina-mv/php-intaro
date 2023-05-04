<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;
use App\System\App;

// переменные из .env
$dotenv = new Dotenv();
$dotenv->load($_SERVER['DOCUMENT_ROOT']. '/.env');

$app = App::getInctance();

$app->run();
