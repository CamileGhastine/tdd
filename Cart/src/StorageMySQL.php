<?php

namespace Cart;

use PDO;

class StorageMySQL implements Storable
{

    public function __construct(private PDO $pdo)
    {
    }

    public function setValue(string $name, float $total, float $unitPrice = 0): void
    {
        $req = $this->pdo->prepare('SELECT * FROM product WHERE name=:name');
        $req->execute([':name' => $name]);
        $req->setFetchMode(PDO::FETCH_OBJ);
        $product = $req->fetch();
        $req->closeCursor();

        if ($product) {
            $newTotal = $product->total + $total;
            $request = "UPDATE product SET total=:total WHERE name=:name";
            $req = $this->pdo->prepare($request);
            $req->execute([':total' => $newTotal, ':name' => $name]);
            $req->closeCursor();

            return;
        }

        if ($unitPrice <= 0) throw new \Exception("Le prix unitaire ne peux pas être égal ou inférieur à 0");

        $request = "INSERT INTO product (name, price, total) VALUES  (:name, :unitPrice, :total)";
        $req = $this->pdo->prepare($request);
        $req->execute([
            ':name' => $name,
            ':unitPrice' => $unitPrice,
            ':total' => $total,
        ]);
        $req->closeCursor();
    }

    public function restore(string $name): void
    {
        $req = $this->pdo->prepare('SELECT * FROM product WHERE name=:name');
        $req->execute([':name' => $name]);
        $req->setFetchMode(PDO::FETCH_OBJ);
        $product = $req->fetch();
        $req->closeCursor();

        if (!$product) throw new \Exception("Vous ne pouvez pas retirer un produit inexistant.");
        
        $req = $this->pdo->prepare('DELETE FROM product WHERE name=:name');
        $req->execute([':name' => $name]);
        $req->closeCursor();
    }

    public function reset(): void
    {
        $req = $this->pdo->query('TRUNCATE product');
    }

    // TODO refactoring responsability ?
    public function total(): float
    {
        $req = $this->pdo->query('SELECT Sum(total) As total FROM product');
        $req->setFetchMode(PDO::FETCH_OBJ);
        $product = $req->fetch();
        $req->closeCursor();

        return $product->total;
    }

    public function getStorage(): array
    {
        $req = $this->pdo->prepare('SELECT * FROM product');
        $req->execute();
        $req->setFetchMode(PDO::FETCH_ASSOC);
        $products = $req->fetchall();
        $req->closeCursor();

        return $products;
    }
}
