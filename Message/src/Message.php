<?php

namespace App;

class Message
{

    public function __construct(
        private string $lang = 'en',
        private array $translates = ['fr' => 'Bonjour les gens !', 'en' => 'Hello World!']
    ) {
    }

    public function get(): string
    {

        return $this->translates[$this->lang];
    }

    public function setLang(string $lang): void
    {
        $this->lang = $lang;
    }
}
