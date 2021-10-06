<?php

use App\Model\Add;
use App\Model\Number;
use App\Model\Divisor;
use PHPUnit\Framework\TestCase;

class ModelTest extends testCase
{
    protected Add $add;
    protected Divisor $divisor;

    public function testType()
    {
        $reflectAdd = new ReflectionClass(Add::class);
        $reflectDivisor = new ReflectionClass(Divisor::class);
        $this->assertTrue($reflectAdd->implementsInterface(('App\\Model\\Calculable')));
        $this->assertTrue($reflectDivisor->implementsInterface(('App\\Model\\Calculable')));
    }

    public function setUp(): void
    {
        $this->add = new Add();
        $this->divisor = new Divisor();
    }

    public function testAdd()
    {
        $num1 = new Number(7);
        $num2 = new Number(8);

        $this->assertSame((string)$this->add->execute($num1, $num2), "15");
    }

    public function testDivisor()
    {
        $num1 = new Number(10);
        $num2 = new Number(2);

        $this->assertSame((string)$this->divisor->execute($num1, $num2), "5");
    }

    public function testDivisionByZero()
    {
        $num1 = new Number(10);
        $num2 = new Number(0);

        $this->expectException(\DivisionByZeroError::class);
        $this->expectExceptionMessage('Impossible de diviser par zéro.');
        $this->divisor->execute($num1, $num2);
    }
}
