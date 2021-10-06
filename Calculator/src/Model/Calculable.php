<?php
namespace App\Model;

interface Calculable
{
    public function execute(Number $num1, Number $num2): NumberString;

}