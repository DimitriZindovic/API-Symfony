<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
#[ApiResource()]
#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('ROLE_ASSISTANT')", securityMessage: 'You are not allowed to get animals'),
        new Post(security: "is_granted('ROLE_ASSISTANT')", securityMessage: 'You are not allowed to create animals'),
        new Get(security: "is_granted('ROLE_ASSISTANT')", securityMessage: 'You are not allowed to get this animal'),
        new Patch(security: "is_granted('ROLE_ASSISTANT')", securityMessage: 'You are not allowed to edit this animal'),
        new Delete(security: "is_granted('ROLE_ASSISTANT')", securityMessage: 'You are not allowed to delete this animal'),
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    private ?string $specie = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['read', 'write'])]
    private ?\DateTimeInterface $bornDate = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    #[Groups(['read', 'write'])]
    private ?Client $client = null;

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

    public function getspecie(): ?string
    {
        return $this->specie;
    }

    public function setspecie(string $specie): static
    {
        $this->specie = $specie;

        return $this;
    }

    public function getBornDate(): ?\DateTimeInterface
    {
        return $this->bornDate;
    }

    public function setBornDate(\DateTimeInterface $bornDate): static
    {
        $this->bornDate = $bornDate;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }
}
