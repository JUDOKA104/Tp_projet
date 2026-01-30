<?php
namespace App\Model;

use App\Core\Database;
use App\Entity\User;
use PDO;
use App\Entity\Product;
use App\Entity\Weapon;
use App\Entity\Rank;

class UserManager
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch();

        if (!$data) return null;

        $user = new User($data['pseudo'], $data['email'], $data['password']);
        $user->setId((int)$data['id']);
        $user->setRole($data['role']);

        return $user;
    }

    public function save(User $user): bool
    {
        // Vérification email existant
        $check = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$user->getEmail()]);
        if ($check->fetch()) {
            return false;
        }

        // --- SÉCURITÉ RENFORCÉE (Poivre + Argon2) ---

        // La clé secrète
        $secretKey = "CubicInfrastructure_Super_Secret_Key_!2026";

        // Hash de la clé secrète (SHA256)
        $hashedKey = hash('sha256', $secretKey);

        // Concaténation (Mot de passe User + Clé Hashée)
        $saltedPassword = $user->getPassword() . $hashedKey;

        // Hashage final avec ARGON2ID
        // Options optionnelles pour Argon2 (mémoire, temps, threads)
        $options = [
            'memory_cost' => 65536, // 64MB
            'time_cost'   => 4,     // Nombre d'itérations
            'threads'     => 1
        ];

        $finalHash = password_hash($saltedPassword, PASSWORD_ARGON2ID, $options);

        $sql = "INSERT INTO users (pseudo, email, password, role) 
            VALUES (:pseudo, :email, :pass, :role)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'pseudo' => $user->getPseudo(),
            'email'  => $user->getEmail(),
            'pass'   => $finalHash, // Hash Argon2id stocké
            'role'   => $user->getRole()
        ]);
    }

    /**
     * Ajoute un produit à l'inventaire de l'utilisateur
     */
    public function addToInventory(int $userId, int $productId): bool
    {
        // On vérifie d'abord si l'utilisateur ne l'a pas déjà (doublon)
        $check = $this->pdo->prepare("SELECT 1 FROM user_inventory WHERE user_id = ? AND product_id = ?");
        $check->execute([$userId, $productId]);
        if ($check->fetch()) {
            return true; // Il l'a déjà, on considère que c'est bon
        }

        $stmt = $this->pdo->prepare("INSERT INTO user_inventory (user_id, product_id) VALUES (?, ?)");
        return $stmt->execute([$userId, $productId]);
    }

    /**
     * Récupère tous les produits possédés par un utilisateur
     * @return Product[]
     */
    public function getInventory(int $userId): array
    {
        $sql = "SELECT p.* FROM products p 
                JOIN user_inventory ui ON p.id = ui.product_id 
                WHERE ui.user_id = :uid 
                ORDER BY ui.purchase_date DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['uid' => $userId]);

        $products = [];
        foreach ($stmt->fetchAll() as $data) {
            // On réutilise la logique de mapping (similaire à ProductManager)
            $item = match ($data['category']) {
                'Arme' => new Weapon($data['name'], (float)$data['price']),
                'Grade' => new Rank($data['name'], (float)$data['price']),
                default => null
            };
            if ($item) {
                $item->setId((int)$data['id']);
                $item->setDescription($data['description']);
                $item->setImage($data['image']);
                $products[] = $item;
            }
        }
        return $products;
    }
}