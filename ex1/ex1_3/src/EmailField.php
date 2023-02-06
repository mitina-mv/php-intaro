<?php
declare(strict_types=1);

namespace Ex2;

require_once ('Field.php');

class EmailField extends Field
{
    public function isValid(): string
    {
        return preg_match("/^[^_\s\W][\w]{3,30}@{1}[a-zA-Z]{2,30}[.]{1}[a-z]{2,10}$/", $this->value) == 1 ? "OK" : "FAIL";
    }
}