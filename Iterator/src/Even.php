<?php

namespace App;

use Iterator;

class Even implements Iterator
{
    private int $positon = 0;

    public function __construct(private int $max)
    {
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->position;
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        $this->position += 2;
    }

    public function valid()
    {
        return $this->position < $this->max;
    }

    /**
     * Get the value of max
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * Set the value of max
     *
     * @return  self
     */
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Get the value of array
     */ 
    public function getArray()
    {
        return $this->array;
    }
}
