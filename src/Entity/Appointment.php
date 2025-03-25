<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Attribute\Groups;
use App\Enum\AppointmentStatus;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;

#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'statut' => 'exact', 'isPaid' => 'exact'])]
#[ApiFilter(DateFilter::class, properties: ['appointmentDate'])]

#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    operations: [
        new GetCollection(
            security: "is_granted('ROLE_VETERINARIAN') or is_granted('ROLE_ASSISTANT') or is_granted('ROLE_DIRECTOR')",
            securityMessage: 'Acces refused : you are not allowed to list appointments.'
        ),
        new Post(
            security: "is_granted('ROLE_ASSISTANT')",
            securityMessage: 'Acces refused : you are not allowed to create an appointment.'
        ),
        new Get(
            security: "is_granted('ROLE_ASSISTANT') or is_granted('ROLE_VETERINARIAN') or object == user",
            securityMessage: 'Acces refused : you are not allowed to see this appointment.'
        ),
        new Patch(
            security: "(is_granted('ROLE_ASSISTANT') or is_granted('ROLE_VETERINARIAN') or object == user) and object.isModifiable()",
            securityMessage: 'Acces refused : you are not allowed to modify this appointment.'
        ),
        new Delete(
            security: "is_granted('ROLE_DIRECTOR') or object == user",
            securityMessage: 'Acces refused : you are not allowed to delete this appointment.'
        ),
    ],

)]

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(groups: 'read')]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['read'])]
    private ?\DateTimeInterface $createdDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(groups: ['read', 'write'])]
    private ?\DateTimeInterface $appointmentDate = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(groups: ['read', 'write'])]
    private ?string $motif = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Groups(groups: ['read', 'write'])]
    private ?bool $isPaid = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(groups: ['read', 'write'])]
    private ?Animal $animal = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(groups: ['read', 'write'])]
    private ?User $assistant = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(groups: ['read', 'write'])]
    private ?User $veterinarian = null;

    #[ORM\Column(type: 'string', enumType: AppointmentStatus::class, length: 20)]
    #[Groups(groups: ['read', 'write'])]
    private ?AppointmentStatus $statut = null;

    /**
     * @var Collection<int, Traitement>
     */
    #[ORM\ManyToMany(targetEntity: Traitement::class)]
    #[Groups(groups: ['read', 'write'])]
    private Collection $traitement;

    public function __construct()
    {
        $this->traitement = new ArrayCollection();
        $this->createdDate = new \DateTime(
            'now',
            new \DateTimeZone('Europe/Paris')
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): static
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getAppointmentDate(): ?\DateTimeInterface
    {
        return $this->appointmentDate;
    }

    public function setAppointmentDate(\DateTimeInterface $appointmentDate): static
    {
        $this->appointmentDate = $appointmentDate;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): static
    {
        $this->motif = $motif;

        return $this;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): static
    {
        $this->animal = $animal;

        return $this;
    }

    public function getAssistant(): ?User
    {
        return $this->assistant;
    }

    public function setAssistant(?User $assistant): static
    {
        if ($assistant !== null && !$assistant->hasRole('ROLE_ASSISTANT')) {
            throw new \InvalidArgumentException("L'utilisateur sélectionné n'est pas un assistant.");
        }

        $this->assistant = $assistant;
        return $this;
    }

    public function getVeterinarian(): ?User
    {
        return $this->veterinarian;
    }

    public function setVeterinarian(?User $veterinarian): static
    {
        if ($veterinarian !== null && !$veterinarian->hasRole('ROLE_VETERINARIAN')) {
            throw new \InvalidArgumentException("L'utilisateur sélectionné n'est pas un vétérinaire.");
        }

        $this->veterinarian = $veterinarian;
        return $this;
    }

    public function getStatut(): ?AppointmentStatus
    {
        return $this->statut;
    }

    public function setStatut(AppointmentStatus $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    /**
     * @return Collection<int, Traitement>
     */
    public function getTraitement(): Collection
    {
        return $this->traitement;
    }

    public function addTraitement(Traitement $traitement): static
    {
        if (!$this->traitement->contains($traitement)) {
            $this->traitement->add($traitement);
        }

        return $this;
    }

    public function removeTraitement(Traitement $traitement): static
    {
        $this->traitement->removeElement($traitement);

        return $this;
    }

    public function isModifiable(): bool
    {
        return $this->statut !== AppointmentStatus::FINISH;
    }

    public function getIsPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setIsPaid(bool $isPaid): static
    {
        $this->isPaid = $isPaid;

        return $this;
    }

}
