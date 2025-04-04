<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ParentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ParentsRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['parent:read']],
    denormalizationContext: ['groups' => ['parent:write']]
)]
class Parents 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['parent:read', 'eleve:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['parent:read', 'parent:write', 'eleve:read'])]
    private ?string $nomParent = null;

    #[ORM\Column(length: 50)]
    #[Groups(['parent:read', 'parent:write'])]
    private ?string $prenomParent = null;

    #[ORM\Column(length: 100, unique: true)]
    #[Groups(['parent:read', 'parent:write'])]
    private ?string $emailParent = null;

    #[ORM\Column(length: 20)]
    #[Groups(['parent:read', 'parent:write'])]
    private ?string $telParent = null;

    #[ORM\Column(length: 255)]
    #[Groups(['parent:read', 'parent:write'])]
    private ?string $adresseParent = null;

    #[ORM\Column(length: 255)]
    #[Groups(['parent:read', 'parent:write'])]
    private ?string $motDePasse = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: Inscription::class, orphanRemoval: true)]
    #[Groups(['parent:read'])]
    private Collection $inscriptions;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: Eleve::class, orphanRemoval: true)]
    private Collection $eleves;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: Contrat::class, orphanRemoval: true)]
    private Collection $contrats;

    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
        $this->eleves = new ArrayCollection();
        $this->contrats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomParent(): ?string
    {
        return $this->nomParent;
    }

    public function setNomParent(string $nomParent): static
    {
        $this->nomParent = $nomParent;
        return $this;
    }

    public function getPrenomParent(): ?string
    {
        return $this->prenomParent;
    }

    public function setPrenomParent(string $prenomParent): static
    {
        $this->prenomParent = $prenomParent;
        return $this;
    }

    public function getEmailParent(): ?string
    {
        return $this->emailParent;
    }

    public function setEmailParent(string $emailParent): static
    {
        $this->emailParent = $emailParent;
        return $this;
    }

    public function getTelParent(): ?string
    {
        return $this->telParent;
    }

    public function setTelParent(string $telParent): static
    {
        $this->telParent = $telParent;
        return $this;
    }

    public function getAdresseParent(): ?string
    {
        return $this->adresseParent;
    }

    public function setAdresseParent(string $adresseParent): static
    {
        $this->adresseParent = $adresseParent;
        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): static
    {
        $this->motDePasse = password_hash($motDePasse, PASSWORD_BCRYPT);
        return $this;
    }

    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): static
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions->add($inscription);
            $inscription->setParent($this);
        }
        return $this;
    }

    public function removeInscription(Inscription $inscription): static
    {
        if ($this->inscriptions->removeElement($inscription)) {
            if ($inscription->getParent() === $this) {
                $inscription->setParent(null);
            }
        }
        return $this;
    }
} 
