<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TreatmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TreatmentRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('ROLE_VETERINARIAN')", securityMessage: 'You are not allowed to get treatments'),
        new Post(security: "is_granted('ROLE_VETERINARIAN')", securityMessage: 'You are not allowed to create treatments'),
        new Get(security: "is_granted('ROLE_VETERINARIAN')", securityMessage: 'You are not allowed to get this treatment'),
        new Patch(security: "is_granted('ROLE_VETERINARIAN')", securityMessage: 'You are not allowed to edit this treatment'),
        new Delete(security: "is_granted('ROLE_VETERINARIAN')", securityMessage: 'You are not allowed to delete this treatment'),
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
class Treatment
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
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    private ?string $price = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    private ?string $duration = null;

    /**
     * @var Collection<int, Visit>
     */
    #[ORM\ManyToMany(targetEntity: Visit::class, mappedBy: 'treatment')]
    private Collection $visits;

    public function __construct()
    {
        $this->visits = new ArrayCollection();
    }

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

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
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

    /**
     * @return Collection<int, Visit>
     */
    public function getVisits(): Collection
    {
        return $this->visits;
    }

    public function addVisit(Visit $visit): static
    {
        if (!$this->visits->contains($visit)) {
            $this->visits->add($visit);
            $visit->addTreatment($this);
        }

        return $this;
    }

    public function removeVisit(Visit $visit): static
    {
        if ($this->visits->removeElement($visit)) {
            $visit->removeTreatment($this);
        }

        return $this;
    }
}
