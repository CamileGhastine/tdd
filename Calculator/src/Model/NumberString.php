<?php

namespace App\Model;

class NumberString
{
    private float $number;

    public function __construct($number)
    {
        $this->number = $number;
    }


    public function __toString()
    {
        return  (string) $this->number;
    }
}
