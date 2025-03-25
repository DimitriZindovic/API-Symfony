<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
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
    forceEager: false,
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    operations: [
        new GetCollection(
            security: "is_granted('ROLE_DIRECTOR')",
            securityMessage: 'Acces refused : you are not allowed to list animals.'
        ),
        new Post(
            security: "is_granted('ROLE_ASSISTANT')",
            securityMessage: 'Acces refused : you are not allowed to create an animal.'
        ),
        new Get(
            security: "is_granted('ROLE_ASSISTANT') or object.owner == user",
            securityMessage: 'Acces refused : you are not allowed to see this animal.'
        ),
        new Patch(
            security: "is_granted('ROLE_DIRECTOR') or object.owner == user",
            securityMessage: 'Acces refused : you are not allowed to modify this animal.'
        ),
        new Delete(
            security: "is_granted('ROLE_DIRECTOR') or object.owner == user",
            securityMessage: 'Acces refused : you are not allowed to delete this animal.'
        ),
    ],

)]

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(groups: 'read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['read', 'write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['read', 'write'])]
    private ?string $species = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(groups: ['read', 'write'])]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups(groups: ['read', 'write'])]
    private ?Media $pictures = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(groups: ['read', 'write'])]
    private ?Client $owner = null;

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

    public function getSpecies(): ?string
    {
        return $this->species;
    }

    public function setSpecies(string $species): static
    {
        $this->species = $species;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getPictures(): ?Media
    {
        return $this->pictures;
    }

    public function setPictures(?Media $pictures): static
    {
        $this->pictures = $pictures;

        return $this;
    }

    public function getOwner(): ?Client
    {
        return $this->owner;
    }

    public function setOwner(?Client $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
