<?php

namespace App\Entity;

use App\Repository\ClientRepository;
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
            security: "is_granted('ROLE_DIRECTOR')",
            securityMessage: 'Acces refused : you are not allowed to list clients.'
        ),
        new Post(
            security: "is_granted('ROLE_DIRECTOR')",
            securityMessage: 'Acces refused : you are not allowed to create a client.'
        ),
        new Get(
            security: "is_granted('ROLE_DIRECTOR') or object == user",
            securityMessage: 'Acces refused : you are not allowed to see this client.'
        ),
        new Patch(
            security: "is_granted('ROLE_DIRECTOR') or object == user",
            securityMessage: 'Acces refused : you are not allowed to modify this client.'
        ),
        new Delete(
            security: "is_granted('ROLE_DIRECTOR') or object == user",
            securityMessage: 'Acces refused : you are not allowed to delete this client.'
        ),
    ],

)]

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
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
    private ?string $firstname = null;

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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }
}
