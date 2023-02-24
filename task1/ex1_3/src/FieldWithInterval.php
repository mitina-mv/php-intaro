<?php
declare(strict_types=1);

namespace Ex3;

require_once 'Field.php';

/**
 * FieldWithInterval
 * Расширяет класс Field, добавляя интервалы
 * 
 * @package Ex3
 * @author mitina_mv
 */
abstract class FieldWithInterval extends Field
{
    /**
     * начальная граница интервала
     * 
     * @var int $n
     */
    protected int $n;

    /**
     * конечная граница интервала
     * 
     * @var int $m
     */
    protected int $m;

    /**
     * Конструктор FieldWithInterval
     */
    public function __construct(string $v, string $t, int $n, int $m)
    {
        // вызываем конструктор родителя
        parent::__construct($v, $t);
        
        $this->n = $n;
        $this->m = $m;
    }
}