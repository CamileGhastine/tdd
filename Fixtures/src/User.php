<?php

namespace App;

class User {

    private string $username;
    private int $id;

    public function __set($name, string $value):void
    {
        $this->$name = $value;
    }

    public function __get(string $name):string
    {
        return $this->$name;
    }

}