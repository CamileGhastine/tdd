<?php

namespace App\Model;

class Divisor implements Calculable
{
    public function execute(Number $a, Number $b): NumberString
    {

        if ((int) $b->getNumber() == 0) throw new \DivisionByZeroError("Impossible de diviser par zéro.");

        return new NumberString($a->getNumber() / $b->getNumber());
    }
}
