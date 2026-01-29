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
        $stmt = $this->pdo->query("SELECT * FROM products ORDER BY category DESC, price ASC");
        $productsData = $stmt->fetchAll();
        $products = [];

        foreach ($productsData as $data) {
            $products[] = $this->mapDataToProduct($data);
        }

        return $products;
    }

    public function find(int $id): ?Product
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if (!$data) return null;

        if ($data['category'] === 'Arme') {
            $item = new Weapon($data['name'], (float)$data['price']);
        } elseif ($data['category'] === 'Grade') {
            $item = new Rank($data['name'], (float)$data['price']);
        } else {
            return null;
        }

        $item->setId((int)$data['id']);
        $item->setDescription($data['description']);
        $item->setImage($data['image']);

        return $item;
    }

    public function update(Product $product): bool
    {
        $sql = "UPDATE products SET name = :name, description = :desc, price = :price, image = :img 
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id'    => $product->getId(),
            'name'  => $product->getName(),
            'desc'  => $product->getDescription(),
            'price' => $product->getPrice(),
            'img'   => $product->getImage()
        ]);
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

    private function mapDataToProduct(array $data): ?Product
    {
        $item = match ($data['category']) {
            'Arme' => new Weapon($data['name'], (float)$data['price']),
            'Grade' => new Rank($data['name'], (float)$data['price']),
            default => null
        };

        if ($item) {
            $item->setId((int)$data['id']);
            $item->setDescription($data['description']);
            $item->setImage($data['image']);
        }
        return $item;
    }
}