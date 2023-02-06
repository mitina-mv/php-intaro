<?php
declare(strict_types=1);

namespace Ex2;

require_once 'Field.php';

class PhoneField extends Field
{
    public function isValid() : string
    {
        return preg_match("/^[+]7 [(][0-9]{3}[)] [0-9]{3}-[0-9]{2}-[0-9]{2}$/", $this->value) == 1 ? "OK" : "FAIL";
    }
}