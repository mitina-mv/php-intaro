<?php
declare(strict_types=1);

namespace Ex3;

require_once 'Field.php';

/**
 * DateField
 * Поле типа даты (D)
 * 
 * @package Ex3
 * @author mitina_mv
 */
class DateField extends Field
{
    public function isValid() : string
    {
        // Создает и возвращает экземпляр класса DateTime, соответствующий заданному формату
        $date = \DateTime::createFromFormat("d.m.Y H:i", $this->value);

        // условия: если дата существует и строковое значение совпадает с одним из возможных форматов
        return $date && (
            $date->format("d.m.Y H:i") == $this->value      // 01.01.2001 01:01
            || $date->format("d.m.Y G:i") == $this->value   // 01.01.2001 1:01
            || $date->format("d.n.Y H:i") == $this->value   // 01.1.2001 01:01
            || $date->format("d.n.Y G:i") == $this->value   // 01.1.2001 1:01
            || $date->format("j.m.Y H:i") == $this->value   // 1.01.2001 01:01
            || $date->format("j.n.Y H:i") == $this->value   // 1.1.2001 01:01
            || $date->format("j.n.Y G:i") == $this->value   // 1.1.2001 1:01
        )? "OK" : "FAIL";
    }
}