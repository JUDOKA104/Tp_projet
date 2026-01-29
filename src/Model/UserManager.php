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
        $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (pseudo, email, password, role) 
                VALUES (:pseudo, :email, :pass, :role)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'pseudo' => $user->getPseudo(),
            'email'  => $user->getEmail(),
            'pass'   => $hashedPassword,
            'role'   => $user->getRole()
        ]);
    }
}