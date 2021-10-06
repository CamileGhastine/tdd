<?php

use PHPUnit\Framework\TestCase;
use App\Calculator;
use Provider\TraitProvider;

class CalculatorTest extends TestCase
{
    use TraitProvider;

    protected $calculator;

    public function setUp(): void
    {
        $this->calculator = new Calculator($_ENV['PRECISION'] ?? 2);
    }

    /**
     * @test testeInstanceOfCalculator test l'instance de Calculator
     */
    public function testInstanceCaluclator():void
    {
        $this->assertInstanceOf(Calculator::class, $this->calculator);
    }

    /**
     * @test testAdd 
     * @dataProvider addProvider
     */
    public function testAddResult($a, $b, $expected):void
    {
        $this->assertSame($expected, $this->calculator->add($a, $b));
    }



    /**
     * @test testDivision
     * @dataProvider divisionProvider
     */
    public function testDivisionResult($a, $b, $expected)
    {
        $this->assertSame($this->calculator->division($a, $b), $expected);
    }

    /**
     * @test Exception division by zero
     */
    public function testDivisionByZeroError(): void
    {
        $this->expectException(\DivisionByZeroError::class);
        $this->expectExceptionMessage('Impossible de diviser par zéro.');
        $this->calculator->division(3,0);

    }


}
