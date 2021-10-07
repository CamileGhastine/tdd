<?php

use PHPUnit\Framework\TestCase;
use Cart\Cart;
use Cart\Product;
use Cart\Storable;

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

        $this->storage = $this->createMock(Storable::class);
        $this->storage->setValue($orange->getName(), $orange->getPrice() * 2 * 1.2);
        $this->storage->setValue($strawberry->getName(), $strawberry->getPrice() * 5 * 1.2);

        $this->cart = new Cart($this->storage);
    }

    /**
     * @test testBuy test add product in Cart
     */
    public function testBuy()
    {
        extract($this->products);

        $this->storage->expects($this->once())->method('setValue')->with($apple->getName(), 37.8);

        $this->cart->buy($apple, 3);
    }

    /**
     * @test testReset test the reset of the storage
     */
    public function testReset()
    {
        $this->storage->expects($this->once())->method('reset');

        $this->cart->reset();
    }

    /**
     * @test testRestore test the reset of one product in the storage
     */
    public function testRestore()
    {
        extract($this->products);

        $this->storage->expects($this->once())->method('restore')->with($strawberry->getName());

        $this->cart->restore($strawberry);
    }

    /**
     * @test testTotal test the price of the storage
     */
    public function testTotal()
    {
        $this->storage->method('getStorage')->willReturn([
            'apple' => 10 * 10.5 * 1.2,
            'orange' => 5 * 7.5 * 1.2,
        ]);

        $this->storage->expects($this->once())->method('getStorage');

        $sum = 10 * 10.5 * 1.2 + 5 * 7.5 * 1.2;

        $this->assertEquals($this->cart->total(), round($sum, 2));
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

        $this->storage->method('getStorage')->willReturn([
            'apple' => 10 * 10.5 * 1.2,
            'orange' => 5 * 7.5 * 1.2,
        ]);
        
        $newValue = 10 * 10.5 * 1.2 - 5 * 10.5 * 1.2;

        $this->storage->expects($this->once())->method('restore')->with($apple->getName());
        $this->storage->expects($this->once())->method('setValue')->with($apple->getName(), $newValue);

        $this->cart->restoreQuantity($apple, 5);
    }

    /**
     * @test testRestoreQuantityExceptionProductNotInTheStorage test if an excpetion is thrown if the product is not in the storage
     */
    public function testRestoreQuantityExceptionProductNotInTheStorage()
    {
        extract($this->products);

        $this->storage->method('getStorage')->willReturn([
            'apple' => 10 * 10.5 * 1.2,
            'orange' => 5 * 7.5 * 1.2,
        ]);

        $this->expectExceptionMessage("Pas de {$strawberry->getName()} dans le panier.");

        $this->cart->restoreQuantity($strawberry, 5);
    }

    /**
     * @test testRestoreQuantityExceptionProductNotEnoughInStorage test if an excpetion is thrown if the quantiyt is too important
     */
    public function testRestoreQuantityExceptionProductNotEnoughInStorage()
    {
        extract($this->products);

        $this->storage->method('getStorage')->willReturn([
            'apple' => 10 * 1.5 * 1.2,
            'orange' => 10 * 1.2 * 1.2,
            'bananas' => 10 * 1.3 * 1.2,
        ]);

        $this->expectExceptionMessage("Il n'y pas assez de {$orange->getName()} dans votre panier pour en retirer 1000");

        $this->cart->restoreQuantity($orange, 1000);
    }
}
