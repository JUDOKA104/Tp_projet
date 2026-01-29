<?php
namespace App\Model;

use App\Core\Database;
use App\Entity\Product;
use App\Entity\Weapon;
use App\Entity\Rank;
use PDO;

class ProductManager
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM products ORDER BY category, name");
        $productsData = $stmt->fetchAll();

        $products = [];

        foreach ($productsData as $data) {
            if ($data['category'] === 'Arme') {
                $item = new Weapon($data['name'], $data['price']);
            } elseif ($data['category'] === 'Grade') {
                $item = new Rank($data['name'], $data['price']);
            } else {
                continue;
            }

            $item->setDescription($data['description']);
            $item->setImage($data['image']);

            $products[] = $item;
        }

        return $products;
    }

    public function add(Product $product): bool
    {
        $sql = "INSERT INTO products (name, description, price, image, category) 
                VALUES (:name, :desc, :price, :img, :cat)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'name'  => $product->getName(),
            'desc'  => $product->getDescription(),
            'price' => $product->getPrice(),
            'img'   => $product->getImage(),
            'cat'   => $product->getCategory()
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}