<?php
declare(strict_types=1);

namespace Ex1;

class Game
{
    private int $id;
    private float $cl;
    private float $cr;
    private float $cd;
    private string $result;

    public function __construct(int $id, float $cl, float $cr, float $cd, string $res)
    {
        $this->id = $id;
        $this->cl = $cl;
        $this->cr = $cr;
        $this->cd = $cd;
        $this->result = $res;

        return $this;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getCoefficientL() : float
    {
        return $this->cl;
    }

    public function getCoefficientR() : float
    {
        return $this->cr;
    }

    public function getCoefficientD() : float
    {
        return $this->cd;
    }

    public function getResult() : string
    {
        return $this->result;
    }
}