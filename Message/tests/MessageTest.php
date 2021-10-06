<?php

use PHPUnit\Framework\TestCase;
use App\Message;

class MessageTest extends TestCase
{
    protected Message $message;

    public function setUp(): void
    {
        $this->message = new Message('en');
    }

    /**
     * @test lang en
     */
    public function testLangEn()
    {
        $this->assertSame("Hello World!", $this->message->get());
    }

    /**
     * @test lang fr
     */
    public function testLangFr()
    {
        $this->message->setLang('fr');
        $this->assertSame("Bonjour les gens !", $this->message->get());
    }

    
}
