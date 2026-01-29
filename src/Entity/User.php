<?php
namespace App\Entity;

class User
{
    private ?int $id = null;
    private string $role = 'USER';

    // PHP 8 Constructor Promotion : On déclare et initialise en même temps
    public function __construct(
        private string $pseudo = '',
        private string $email = '',
        private string $password = ''
    ) {}

    public function getId(): ?int { return $this->id; }

    public function setId(int $id): self { $this->id = $id; return $this; }

    public function getPseudo(): string { return $this->pseudo; }
    public function setPseudo(string $pseudo): self { $this->pseudo = $pseudo; return $this; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }

    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): self { $this->password = $password; return $this; }

    public function getRole(): string { return $this->role; }
    public function setRole(string $role): self {
        if (in_array($role, ['USER', 'ADMIN'])) {
            $this->role = $role;
        }
        return $this;
    }

    public function isAdmin(): bool { return $this->role === 'ADMIN'; }
}