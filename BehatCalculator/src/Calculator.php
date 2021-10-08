<?php

namespace App;

class Calculator
{
    private array $memory = [];

    public function add(float $number1, float $number2): float
    {
        $result = $number1 + $number2;
        $this->memory[] = $result;
        
        if ($result >= 200) {
            throw new \RangeException('Le resultat est superieur à 200 et ne peut être mémorisé');
            unset($this->memory[array_key_last($this->memory[])]);
        } 
     

        

        return $result;
    }

    public function getMemory(): array
    {
        return $this->memory;
    }

    public function reset(): self
    {
        $this->memory = [];

        return $this;
    }


    /**
     * @Then le résultat doit être :arg1
     */
    public function leResultatDoitEtre($arg1)
    {
        $memory = $this->calculator->getMemory();

        if (end($memory) != $arg1) throw new Exception('Mauvaise addition');
    }
}
