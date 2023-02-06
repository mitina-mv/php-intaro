<?php
declare(strict_types=1);

namespace Ex2;

require_once 'FieldWithInterval.php';

class StringField extends FieldWithInterval
{
    public function isValid() : string
    {
        return preg_match("/^[\w\s\W]{" . $this->n . "," .$this->m . "}+$/", $this->value) == 1 ? "OK" : "FAIL";
    }
}