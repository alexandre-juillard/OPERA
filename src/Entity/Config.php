<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ConfigRepository;

#[ORM\Entity(repositoryClass: ConfigRepository::class)]

class Config
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    
    private ?int $id;

    #[ORM\Column(type: "string", length: 255)]

    private ?string $name = null;

    #[ORM\Column(type: "string", length: 255)]

    private ?string $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }
}
