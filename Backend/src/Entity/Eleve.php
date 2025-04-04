<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\EleveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EleveRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['eleve:read']],
    denormalizationContext: ['groups' => ['eleve:write']]
)]
class Eleve
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['eleve:read', 'groupe:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['eleve:read', 'eleve:write'])]
    private ?string $nomEleve = null;

    #[ORM\Column(length: 50)]
    #[Groups(['eleve:read', 'eleve:write'])]
    private ?string $prenomEleve = null;

    #[ORM\Column(length: 20)]
    #[Groups(['eleve:read', 'eleve:write'])]
    private ?string $niveau = null;

    #[ORM\Column(length: 100)]
    #[Groups(['eleve:read', 'eleve:write'])]
    private ?string $etablissementScolaire = null;

    #[ORM\Column(length: 20)]
    #[Groups(['eleve:read', 'eleve:write'])]
    private ?string $telEleve = null;

    #[ORM\ManyToOne(inversedBy: 'eleves')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['eleve:read', 'eleve:write'])]
    private ?Centre $centre = null;

    #[ORM\ManyToOne(inversedBy: 'eleves')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['eleve:read', 'eleve:write'])]
    private ?Parents $parent = null;

    /**
     * @var Collection<int, Groupe>
     */
    #[ORM\ManyToMany(targetEntity: Groupe::class, mappedBy: 'eleves')]
    #[Groups(['eleve:read', 'eleve:write'])]
    private Collection $groupes;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEleve(): ?string
    {
        return $this->nomEleve;
    }

    public function setNomEleve(string $nomEleve): static
    {
        $this->nomEleve = $nomEleve;
        return $this;
    }

    public function getPrenomEleve(): ?string
    {
        return $this->prenomEleve;
    }

    public function setPrenomEleve(string $prenomEleve): static
    {
        $this->prenomEleve = $prenomEleve;
        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): static
    {
        $this->niveau = $niveau;
        return $this;
    }

    public function getEtablissementScolaire(): ?string
    {
        return $this->etablissementScolaire;
    }

    public function setEtablissementScolaire(string $etablissementScolaire): static
    {
        $this->etablissementScolaire = $etablissementScolaire;
        return $this;
    }

    public function getTelEleve(): ?string
    {
        return $this->telEleve;
    }

    public function setTelEleve(string $telEleve): static
    {
        $this->telEleve = $telEleve;
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

    public function getParent(): ?Parents
    {
        return $this->parent;
    }

    public function setParent(?Parents $parent): static
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return Collection<int, Groupe>
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): static
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->addEleve($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): static
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeEleve($this);
        }

        return $this;
    }
}
