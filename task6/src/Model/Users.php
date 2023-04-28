<?php

namespace App\Model;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

 #[ORM\Entity(repositoryClass: ProductRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column]
    private int $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    // ... методы геттера и сеттера
}