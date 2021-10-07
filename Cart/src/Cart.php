<?php

namespace Cart;

use Exception;

class Cart
{

    // .2 <=> 0.2
    public function __construct(private Storable $storage, private float $tva = .2)
    {
    }

    public function buy(Productable $p, int $quantity): void
    {

        $total = abs($quantity * $p->getPrice() * ($this->tva + 1));

        $this->storage->setValue($p->getName(), $total);
    }

    public function reset(): void
    {
        $this->storage->reset();
    }

    public function restore(Productable $p): void
    {

        $this->storage->restore($p->getName());
    }

    public function total(): float
    {

        return round(array_sum($this->storage->getStorage()), $_ENV['APP_PRECISION'] ?? 3);
    }

    public function restoreQuantity(Productable $p, int $quantity)
    {
        $key = $p->getName();

        if (!array_key_exists($key, $this->storage->getStorage()))
            throw new Exception("Pas de $key dans le panier.");

        $value = $this->storage->getStorage()[$key];
        if ($value < $quantity * $p->getPrice() * (1 + $this->tva))
            throw new Exception("Il n'y pas assez de $key dans votre panier pour en retirer $quantity");

        $this->storage->restore($key);
        $this->storage->setValue($key, $value - $quantity * $p->getPrice() * (1 + $this->tva));
    }
}
