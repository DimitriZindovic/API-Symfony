<?php

namespace App\Entity;

use App\Repository\TraitementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Attribute\Groups;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;

#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    operations: [
        new GetCollection(
            security: "is_granted('ROLE_VETERINARIAN')",
            securityMessage: 'Acces refused : you are not allowed to list treatments.'
        ),
        new Post(
            security: "is_granted('ROLE_VETERINARIAN')",
            securityMessage: 'Acces refused : you are not allowed to create a treatment.'
        ),
        new Get(
            security: "is_granted('ROLE_VETERINARIAN') or object.owner == user",
            securityMessage: 'Acces refused : you are not allowed to see this treatment.'
        ),
        new Patch(
            security: "is_granted('ROLE_VETERINARIAN') or object.owner == user",
            securityMessage: 'Acces refused : you are not allowed to modify this treatment.'
        ),
        new Delete(
            security: "is_granted('ROLE_VETERINARIAN') or object.owner == user",
            securityMessage: 'Acces refused : you are not allowed to delete this treatment.'
        ),
    ],

)]
#[ORM\Entity(repositoryClass: TraitementRepository::class)]
class Traitement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(groups: 'read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['read', 'write'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(groups: ['read', 'write'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(groups: ['read', 'write'])]
    private ?int $price = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['read', 'write'])]
    private ?string $duration = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): static
    {
        $this->duration = $duration;

        return $this;
    }
}
