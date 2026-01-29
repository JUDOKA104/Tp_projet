<?php
namespace App\Entity;

abstract class Product
{
    protected ?int $id = null;
    protected string $name;
    protected ?string $description = null;
    protected float $price;
    protected ?string $image = null;
    protected string $category;

    public function __construct(string $name, float $price)
    {
        $this->name = $name;
        $this->price = $price;
    }

    public function getId(): ?int { return $this->id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getPrice(): float { return $this->price; }
    public function setPrice(float $price): self { $this->price = $price; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $desc): self { $this->description = $desc; return $this; }

    public function getImage(): ?string { return $this->image; }
    public function setImage(?string $image): self { $this->image = $image; return $this; }

    public function getCategory(): string { return $this->category; }

    abstract public function getDisplayType(): string;
}