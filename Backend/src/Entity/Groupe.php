<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GroupeRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['groupe:read']],
    denormalizationContext: ['groups' => ['groupe:write']]
)]
class Groupe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['groupe:read', 'eleve:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['groupe:read', 'groupe:write', 'eleve:read'])]
    private ?string $nomGroupe = null;

    #[ORM\Column(length: 50)]
    #[Groups(['groupe:read', 'groupe:write'])]
    private ?string $typeGroupe = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(['groupe:read', 'groupe:write'])]
    private ?string $avatarGroupe = null;

    #[ORM\Column(length: 50)]
    #[Groups(['groupe:read', 'groupe:write'])]
    private ?string $niveauGroupe = null;

    #[ORM\Column]
    #[Groups(['groupe:read', 'groupe:write'])]
    private ?int $capaciteGroupe = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['groupe:read', 'groupe:write'])]
    private ?string $descriptionGroupe = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['groupe:read', 'groupe:write'])]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['groupe:read', 'groupe:write'])]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups(['groupe:read', 'groupe:write'])]
    private ?\DateTimeInterface $heureDebut = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups(['groupe:read', 'groupe:write'])]
    private ?\DateTimeInterface $heureFin = null;

    #[ORM\Column(length: 50)]
    #[Groups(['groupe:read', 'groupe:write'])]
    private ?string $matieresGroupe = null;

    #[ORM\ManyToOne(inversedBy: 'groupes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['groupe:read', 'groupe:write'])]
    private ?Salle $salle = null;

    #[ORM\ManyToOne(inversedBy: 'groupes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['groupe:read', 'groupe:write'])]
    private ?Prof $prof = null;

    #[ORM\ManyToOne(inversedBy: 'groupes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['groupe:read', 'groupe:write'])]
    private ?Centre $centre = null;

    /**
     * @var Collection<int, Eleve>
     */
    #[Groups(['groupe:read', 'groupe:write'])]
    #[ORM\ManyToMany(targetEntity: Eleve::class, inversedBy: 'groupes')]
    private Collection $eleves;

    /**
     * @var Collection<int, Inscription>
     */
    #[Groups(['groupe:read'])]
    #[ORM\OneToMany(targetEntity: Inscription::class, mappedBy: 'groupe', orphanRemoval: true)]
    private Collection $inscriptions;

    #[Groups(['groupe:read', 'groupe:write', 'eleve:read'])]
    #[ORM\Column(length: 7)]
    private ?string $backgroundColor = null;

    public function __construct()
    {
        $this->eleves = new ArrayCollection();
        $this->inscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomGroupe(): ?string
    {
        return $this->nomGroupe;
    }

    public function setNomGroupe(string $nomGroupe): static
    {
        $this->nomGroupe = $nomGroupe;
        return $this;
    }

    public function getTypeGroupe(): ?string
    {
        return $this->typeGroupe;
    }

    public function setTypeGroupe(string $typeGroupe): static
    {
        $this->typeGroupe = $typeGroupe;
        return $this;
    }

    public function getAvatarGroupe(): ?string
    {
        return $this->avatarGroupe;
    }

    public function setAvatarGroupe(string $avatarGroupe): static
    {
        $this->avatarGroupe = $avatarGroupe;
        return $this;
    }

    public function getNiveauGroupe(): ?string
    {
        return $this->niveauGroupe;
    }

    public function setNiveauGroupe(string $niveauGroupe): static
    {
        $this->niveauGroupe = $niveauGroupe;
        return $this;
    }

    public function getCapaciteGroupe(): ?int
    {
        return $this->capaciteGroupe;
    }

    public function setCapaciteGroupe(int $capaciteGroupe): static
    {
        $this->capaciteGroupe = $capaciteGroupe;
        return $this;
    }

    public function getDescriptionGroupe(): ?string
    {
        return $this->descriptionGroupe;
    }

    public function setDescriptionGroupe(?string $descriptionGroupe): static
    {
        $this->descriptionGroupe = $descriptionGroupe;
        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    public function getHeureDebut(): ?\DateTimeInterface
    {
        return $this->heureDebut;
    }

    public function setHeureDebut(\DateTimeInterface $heureDebut): static
    {
        $this->heureDebut = $heureDebut;
        return $this;
    }

    public function getHeureFin(): ?\DateTimeInterface
    {
        return $this->heureFin;
    }

    public function setHeureFin(\DateTimeInterface $heureFin): static
    {
        $this->heureFin = $heureFin;
        return $this;
    }

    public function getMatieresGroupe(): ?string
    {
        return $this->matieresGroupe;
    }

    public function setMatieresGroupe(string $matieresGroupe): static
    {
        $this->matieresGroupe = $matieresGroupe;
        return $this;
    }

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): static
    {
        $this->salle = $salle;
        return $this;
    }

    public function getProf(): ?Prof
    {
        return $this->prof;
    }

    public function setProf(?Prof $prof): static
    {
        $this->prof = $prof;
        return $this;
    }

    public function getCentre(): ?Centre
    {
        return $this->centre;
    }

    public function setCentre(?Centre $centre): static
    {
        $this->centre = $centre;
        return $this;
    }

    /**
     * @return Collection<int, Eleve>
     */
    public function getEleves(): Collection
    {
        return $this->eleves;
    }

    public function addEleve(Eleve $eleve): static
    {
        if (!$this->eleves->contains($eleve)) {
            $this->eleves[] = $eleve;
        }

        return $this;
    }

    public function removeEleve(Eleve $eleve): static
    {
        $this->eleves->removeElement($eleve);
        return $this;
    }

    /**
     * @return Collection<int, Inscription>
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): static
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions->add($inscription);
            $inscription->setGroupe($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): static
    {
        if ($this->inscriptions->removeElement($inscription)) {
            if ($inscription->getGroupe() === $this) {
                $inscription->setGroupe(null);
            }
        }

        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(string $backgroundColor): static
    {
        $this->backgroundColor = $backgroundColor;
        return $this;
    }
}
