<?php

use PHPUnit\Framework\TestCase;
use App\Even;

class EvenTest extends TestCase
{
    protected $even;

    public function setup(): void
    {
        $this->even = new Even(max: 14);
    }

    public function testEven()
    {
        $numbers = "";
        foreach($this->even as $number) {
            $numbers .= (string)$number;
        }
        
        $this->assertSame("024681012",  $numbers);
    }

    public function testIterrable()
    {
        $this->assertTrue(is_iterable($this->even));
    }
}