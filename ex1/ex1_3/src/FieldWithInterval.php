<?php
declare(strict_types=1);

namespace Ex2;

require_once 'Field.php';

abstract class FieldWithInterval extends Field
{
    protected int $n;
    protected int $m;

    public function __construct(string $v, string $t, int $n, int $m)
    {
        parent::__construct($v, $t);
        
        $this->n = $n;
        $this->m = $m;
    }
}