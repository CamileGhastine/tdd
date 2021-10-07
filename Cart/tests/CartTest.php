<?php

use PHPUnit\Framework\TestCase;
use Cart\Cart;
use Cart\Product;

class CartTest extends TestCase
{
    private $cart;
    private $storage;
    private $products;

    public function setup(): void
    {
        $this->products = [
            'apple' => new Product('apple', 10.5),
            'raspberry' => new Product('raspberry', 13),
            'strawberry' => new Product('strawberry', 7.5),
            'orange' => new Product('orange', 7.5),
        ];
        extract($this->products);
        
        $this->storage = new MockStorage;
        $this->storage->setValue($orange->getName(), $orange->getPrice()*2*1.2);
        $this->storage->setValue($strawberry->getName(), $strawberry->getPrice()*5*1.2);

        $this->cart = new Cart($this->storage);
    }

    /**
     * @test testBuy test add product in Cart
     */
    public function testBuy()
    {
        extract($this->products);
        $this->cart->buy($apple,3);
        $key = array_key_last($this->storage->getStorage());
        $value = $this->storage->getStorage()[$key];

        $this->assertSame($key, 'apple');
        $this->assertSame($value, 3*10.5*1.2);
    }

    /**
     * @test testReset test the reset of the storage
     */
    public function testReset()
    {
        $this->cart->reset();

        $this->assertEmpty($this->storage->getStorage());
    }

    /**
     * @test testRestore test the reset of one product in the storage
     */
    public function testRestore()
    {
        extract($this->products);

        $this->assertContains($strawberry->getPrice()*5*1.2, $this->storage->getStorage());

        $this->cart->restore($strawberry);
        $this->assertContains($orange->getPrice()*2*1.2, $this->storage->getStorage());
        $this->assertNotContains($strawberry->getPrice()*5*1.2, $this->storage->getStorage());
    }

    /**
     * @test testTotal test the price of the storage
     */
    public function testTotal()
    {
        $this->assertSame($this->cart->total(), 45.0 + 18.0);
    }

    /**
     * @test testTVAByDefault test if the TVA is set by default at 0.2
     */
    public function testTVAByDefault()
    {
        $rp = new ReflectionProperty(Cart::class, 'tva');
        $rp->setAccessible(true);
        $defaultTVA =  $rp->getValue(new Cart($this->storage));

        $this->assertSame(0.2, $defaultTVA);
    }
    
    /**
     * @test testRestoreQuantity test if we can remove a specific quantity of one product
     */
    public function testRestoreQuantity()
    {
        extract($this->products);

        $this->cart->restoreQuantity($strawberry, 2);

        $key = $strawberry->getName();
        $value = $this->storage->getStorage()[$key];

        $this->assertSame($key, 'strawberry');
        $this->assertSame($value, 3*7.5*1.2);
    }

    /**
     * @test testRestoreQuantityExceptionProductNotInTheStorage test if an excpetion is thrown if the product is not in the storage
     */
    public function testRestoreQuantityExceptionProductNotInTheStorage()
    {
        extract($this->products);

        $this->expectExceptionMessage("Pas de {$apple->getName()} dans le panier.");

        $this->cart->restoreQuantity($apple, 3);
    }

    /**
     * @test testRestoreQuantityExceptionProductNotEnoughInStorage test if an excpetion is thrown if the quantiyt is too important
     */
    public function testRestoreQuantityExceptionProductNotEnoughInStorage()
    {
        extract($this->products);

        $this->expectExceptionMessage("Il n'y pas assez de {$orange->getName()} dans votre panier pour en retirer 1000");

        $this->cart->restoreQuantity($orange, 1000);
    }
}
