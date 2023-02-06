<?php
declare(strict_types=1);

namespace Ex2;

require_once 'Field.php';

class DateField extends Field
{
    public function isValid() : string
    {
        $date = \DateTime::createFromFormat("d.m.Y H:i", $this->value);
        return $date && $date->format("d.m.Y H:i") == $date ? "OK" : "FAIL";
    }
}