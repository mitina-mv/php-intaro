<?php
declare(strict_types=1);

namespace Ex3;

require_once 'Field.php';

/**
 * PhoneField
 * Поле типа номер телефона (P)
 * 
 * @package Ex3
 * @author mitina_mv 
 */
class PhoneField extends Field
{
    /**
     * Проверяет валидность телефона маске +7 (999) 999-99-99
     */
    public function isValid() : string
    {
        return preg_match("/^[+]7 [(][0-9]{3}[)] [0-9]{3}-[0-9]{2}-[0-9]{2}$/", $this->value) == 1 ? "OK" : "FAIL";
    }
}