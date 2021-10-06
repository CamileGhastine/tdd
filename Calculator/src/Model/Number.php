<?php

namespace App\Model;

class Number
{
private float $number;

    public function __construct($number)
    {
        $this->number = $number;
    }

    /**
     * Get the value of number
     */ 
    public function getNumber():float
    {
        return $this->number;
    }

    /**
     * Set the value of number
     *
     * @return  self
     */ 
    public function setNumber(float $number):void
    {
        $this->number = $number;

    }
}
