<?php

use PHPUnit\Framework\TestCase;
use Cart\{Product, StorageMySQL};

class StorageMySQLTest extends TestCase
{
    private static $pdo;
    private array $products;

    public function setup(): void
    {
        $this->products = [
            'apple' => new Product('apple', 10.5),
            'raspberry' => new Product('raspberry', 13),
            'strawberry' => new Product('strawberry', 7.5),
            'orange' => new Product('orange', 7.5),
        ];

        $this->storage = new StorageMySQL(self::$pdo);
    }

    public static function setUpBeforeClass(): void
    {
        self::$pdo = new PDO("mysql:host=localhost:3306; dbname=fruittest", $_ENV['APP_USERNAME'], $_ENV['APP_PASSWORD']);
    }


    private function getProductFromDB(string $name)
    {
        $req = self::$pdo->query("SELECT * FROM product WHERE name='" . $name . "'");
        $req->setFetchMode(PDO::FETCH_OBJ);
        $product = $req->fetch();
        $req->closeCursor();
        return $product;
    }

    /**
     * @test testsetValuesUpdateInDB test if we update correctly the DB by setValue Method
     */
    public function testsetValuesUpdateInDB()
    {
        extract($this->products);
        $initialApple = $this->getProductFromDB($apple->getName());

        $this->storage->setValue($apple->getName(), $apple->getPrice() * 2 * 1.2);
        $newApple = $this->getProductFromDB($apple->getName());
        $this->assertEquals($newApple->total, $initialApple->total + $apple->getPrice() * 2 * 1.2);
    }

    /**
     * @test testsetValuesInsertInDB test if we insert correctly the DB by setValue Method
     */
    public function testsetValuesInsertInDB()
    {
        extract($this->products);
        $initialOrange = $this->getProductFromDB($orange->getName());
        $this->assertFalse($initialOrange);

        $this->storage->setValue($orange->getName(), $orange->getPrice() * 2 * 1.2, $orange->getPrice());
        $neworange = $this->getProductFromDB($orange->getName());
        $this->assertEquals($neworange->total, $orange->getPrice() * 2 * 1.2);
        $this->assertEquals($neworange->price, $orange->getPrice());
        $this->assertEquals($neworange->name, $orange->getName());
    }

    /**
     * @test testSestValueInsertExceptionPriceAt0 test if we set a unit price at 0 or lower setValue Method
     */
    public function testSestValueInsertExceptionPriceAt0()
    {
        extract($this->products);
        $initialOrange = $this->getProductFromDB($orange->getName());
        $this->assertFalse($initialOrange);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Le prix unitaire ne peux pas être égal ou inférieur à 0");
        $this->storage->setValue($orange->getName(), $orange->getPrice() * 2 * 1.2, 0);
    }

    /**
     * @test testRestoreException test if we attempt a product from DB by Restore Methd
     */
    public function testRestoreException()
    {
        extract($this->products);
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Vous ne pouvez pas retirer un produit inexistant.");
        $this->storage->restore($orange->getName());
    }
    /**
     * @test testRestore test if we remove a product from DB by Restore Methd
     */
    public function testRestore()
    {
        extract($this->products);
        $initialApple = $this->getProductFromDB($apple->getName());
        $this->assertEquals($initialApple->name, $apple->getName());

        $this->storage->restore($apple->getName());
        $finalApple = $this->getProductFromDB($apple->getName());
        $this->assertFalse($finalApple);
    }

    private function getProducts() {
        $req = self::$pdo->query("SELECT * FROM product");
        $req->setFetchMode(PDO::FETCH_OBJ);
        $products = $req->fetchAll();
        $req->closeCursor();
        return $products;
    }
    /**
     * @test TestReset test if the storage is reset
     */
    public function testReset()
    {
        $this->assertCount(3, $this->getProducts());
        $this->storage->reset();
        $this->assertCount(0, $this->getProducts());
    }

    /**
     * @test testGetTotalStorage test if we retrieve the total amount of the storage
     */
    public function testGetTotalStorage()
    {
        $this->assertEquals($this->storage->total(), 25.2);
    }

    /**
     * @test testGetStorage test if the storage is return
     */
    public function testGetStorage()
    {
        extract($this->products);

        $products = $this->storage->getStorage();
        $this->assertEquals($products[0]["name"], $apple->getName());
        $this->assertEquals($products[1]["name"], $raspberry->getName());
        $this->assertEquals($products[2]["name"], $strawberry->getName());
            
        $this->assertCount(3, $products);
    }


    public function tearDown(): void
    {
        self::$pdo->exec("
        DROP DATABASE IF EXISTS fruittest ;
        CREATE DATABASE fruittest DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
        use fruittest;
        CREATE TABLE IF NOT EXISTS 
        product (
            ID INT NOT NULL AUTO_INCREMENT, 
            name VARCHAR(100), 
            price DECIMAL(7,2), 
            total DECIMAL(7,2) NOT NULL DEFAULT 0.00, 
            PRIMARY KEY(id) )ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO product (name, price, total) VALUES  ('apple', 10.5, 25.2), ('raspberry',13, 0), ('strawberry', 7.8, 0);
        ");
    }

    public static function tearDownAfterClass(): void
    {
        self::$pdo = null;
    }
}
