<?php
namespace App\Entity;

class Weapon extends Product
{
    public function __construct(string $name, float $price)
    {
        parent::__construct($name, $price);
        $this->category = 'Arme';
    }

    public function getDisplayType(): string
    {
        return "⚔️ Arme de combat";
    }
}