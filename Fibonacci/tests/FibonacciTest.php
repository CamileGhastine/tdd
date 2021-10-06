<?php

use PHPUnit\Framework\TestCase;
use App\Fibonacci;

class FibonacciTest extends testCase
{
    private  $fibonnaci;
    private array $sequence = [0, 1, 1, 2, 3, 5, 8, 13, 21, 34, 55];

    public function setup(): void
    {
        $this->fibonnaci = new Fibonacci(term: 10);
    }

    /**
     * @test Test the first two terms (0 and 1) of the Fibonacci sequence
     */
    public function testFindFirstAndSecondTerm()
    {
        $this->assertSame(0, $this->fibonnaci->find(0));
        $this->assertSame(1, $this->fibonnaci->find(1));
    }

    /**
     * @test test any term of the sequence (except the first and second one)
     */
    public function testFindTerm()
    {
        for ($i = 2; $i < 10; $i++) {
            $this->assertSame(
                $this->fibonnaci->find($i),
                $this->fibonnaci->find($i - 1) + $this->fibonnaci->find($i - 2)
            );
        }
    }

    /**
     * @test test find return an integer
     */
    public function testFindReturnType()
    {
        $this->assertTrue(is_int($this->fibonnaci->find(5)));
    }

    /**
     * @test Test the integral sequence
     */
    public function testAllSequence()
    {
        $this->assertSame($this->sequence, $this->fibonnaci->all());
    }

    /**
     * @test test find return an array
     */
    public function testAllReturnType()
    {
        $this->assertTrue(is_array($this->fibonnaci->all()));
    }
}
