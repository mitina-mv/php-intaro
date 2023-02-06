<?php

require_once './src/DateField.php';
require_once './src/PhoneField.php';
require_once './src/StringField.php';
require_once './src/IntField.php';

use Ex2\DateField;
use Ex2\PhoneField;
use Ex2\StringField;

$phone = new PhoneField('+7 (900) 899-09-87', "P");
echo $phone->isValid();

$date = new DateField('17.01.2018 13:38', 'D');
echo $date->isValid();

$str = new StringField('kjas sdj', 'S', 1, 90);
echo $str->isValid();

