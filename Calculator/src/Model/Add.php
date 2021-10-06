<?php

namespace App\Model;

class Add implements Calculable
{

    public function execute(Number $a, Number $b): NumberString
    {

        return new NumberString($a->getNumber() + $b->getNumber());
    }
}
