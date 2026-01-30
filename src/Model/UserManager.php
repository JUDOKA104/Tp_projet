<?php
namespace App\Model;

use App\Core\Database;
use App\Entity\User;
use PDO;

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
}