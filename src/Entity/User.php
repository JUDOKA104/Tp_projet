<?php
namespace App\Entity;

class User
{
    private ?int $id = null;
    private string $pseudo;
    private string $email;
    private string $password;
    private string $role;
    public function __construct(string $pseudo = '', string $email = '', string $password = '')
    {
        $this->pseudo = $pseudo;
        $this->email = $email;
        $this->password = $password;
        $this->role = 'USER';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        if (in_array($role, ['USER', 'ADMIN'])) {
            $this->role = $role;
        }
        return $this;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'ADMIN';
    }
}