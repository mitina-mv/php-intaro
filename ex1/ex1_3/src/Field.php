<?php
declare(strict_types=1);

namespace Ex2;

abstract class Field
{
    protected string $value;
    protected string $type;

    public function __construct(string $v, string $t)
    {
        $this->type = $t;
        $this->value = $v;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getType()
    {
        return $this->type;
    }

    abstract public function isValid() : string;
}