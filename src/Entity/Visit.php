<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\VisitRepository;
use App\State\UserPasswordHasherProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: VisitRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('ROLE_VETERINARIAN') or is_granted('ROLE_ASSISTANT')", securityMessage: 'You are not allowed to get visits'),
        new Post(security: "is_granted('ROLE_ASSISTANT')", securityMessage: 'You are not allowed to create visits'),
        new Get(security: "is_granted('ROLE_VETERINARIAN') or is_granted('ROLE_ASSISTANT')", securityMessage: 'You are not allowed to get this visit'),
        new Patch(security: "is_granted('ROLE_VETERINARIAN') or is_granted('ROLE_ASSISTANT')", securityMessage: 'You are not allowed to edit this visit'),
        new Delete(security: "is_granted('ROLE_VETERINARIAN') or is_granted('ROLE_ASSISTANT')", securityMessage: 'You are not allowed to delete this visit'),
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    forceEager: false
)]
class Visit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read')]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups('read')]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['read', 'write'])]
    private ?\DateTimeInterface $dateVisit = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    private ?string $reason = null;

    #[ORM\ManyToOne(inversedBy: 'visits')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read', 'write'])]
    private ?User $assistant = null;

    #[ORM\ManyToOne(inversedBy: 'visits')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read', 'write'])]
    private ?User $veterinarian = null;

    #[ORM\Column(length: 255)]
    #[Groups('read')]
    private ?string $status = null;

    /**
     * @var Collection<int, Treatment>
     */
    #[ORM\ManyToMany(targetEntity: Treatment::class, inversedBy: 'visits')]
    private Collection $treatment;

    public function __construct()
    {
        $this->treatment = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getDateVisit(): ?\DateTimeInterface
    {
        return $this->dateVisit;
    }

    public function setDateVisit(\DateTimeInterface $dateVisit): static
    {
        $this->dateVisit = $dateVisit;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): static
    {
        $this->reason = $reason;

        return $this;
    }

    public function getAssistant(): ?User
    {
        return $this->assistant;
    }

    public function setAssistant(?User $assistant): static
    {
        $this->assistant = $assistant;

        return $this;
    }

    public function getVeterinarian(): ?User
    {
        return $this->veterinarian;
    }

    public function setVeterinarian(?User $veterinarian): static
    {
        $this->veterinarian = $veterinarian;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Treatment>
     */
    public function getTreatment(): Collection
    {
        return $this->treatment;
    }

    public function addTreatment(Treatment $treatment): static
    {
        if (!$this->treatment->contains($treatment)) {
            $this->treatment->add($treatment);
        }

        return $this;
    }

    public function removeTreatment(Treatment $treatment): static
    {
        $this->treatment->removeElement($treatment);

        return $this;
    }
}
