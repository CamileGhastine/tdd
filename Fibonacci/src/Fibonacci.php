<?php

namespace App;

class Fibonacci
{
    private array $sequence = [];

    public function __construct(private int $term)
    {
    }

    public function find(int $termPosition): int
    {
        $this->calculSequence();

        return $this->sequence[$termPosition];
    }

    public function all(): array
    {
        $this->calculSequence();

        return $this->sequence;
    }

    private function calculSequence()
    {
        if (!empty($sequence)) {
            return $this->sequence;
        }

        $this->sequence = [0, 1];

        for ($i=2; $i<=$this->term; $i++) {
            $this->sequence[] = $this->sequence[$i-1] + $this->sequence[$i-2];
        }
    }
}
