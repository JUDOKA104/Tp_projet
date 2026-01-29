<?php
namespace App\Entity;

class Rank extends Product
{
    public function __construct(string $name, float $price)
    {
        parent::__construct($name, $price);
        $this->category = 'Grade';
    }

    public function getDisplayType(): string
    {
        return "👑 Grade VIP";
    }
}