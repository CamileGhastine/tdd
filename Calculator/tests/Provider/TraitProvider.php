<?php

namespace Provider;

trait TraitProvider
{
    public function addProvider(): array
    {
        return [
            [2, 3, 5.00],
            [2.1, 3.21, 5.31],
            [2.1, 3.214, 5.31],
            [2.1, 3.215, 5.32],
            [2.1, 3.216, 5.32],
        ];
    }

    public function divisionProvider()
    {
        return [
            [10, 2, 5.00],
            [10, 3, 3.33],
        ];
    }
}