<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CentreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CentreRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['centre:read']],
    denormalizationContext: ['groups' => ['centre:write']]
)]
class Centre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['centre:read', 'eleve:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['centre:read', 'centre:write', 'eleve:read', 'groupe:read'])]
    private ?string $nomCentre = null;

    #[ORM\Column]
    #[Groups(['centre:read', 'centre:write', 'eleve:read'])]
    private ?int $nbInscrits = null;

    #[ORM\ManyToOne(inversedBy: 'centres')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['centre:read', 'centre:write', 'eleve:read'])]
    private ?Gerant $gerant = null;

    /**
     * @var Collection<int, Contrat>
     */
    #[ORM\OneToMany(targetEntity: Contrat::class, mappedBy: 'centre', orphanRemoval: true)]
    private Collection $contrats;

    /**
     * @var Collection<int, Eleve>
     */
    #[ORM\OneToMany(targetEntity: Eleve::class, mappedBy: 'centre', orphanRemoval: true)]
    private Collection $eleves;

    /**
     * @var Collection<int, Prof>
     */
    #[ORM\ManyToMany(targetEntity: Prof::class, mappedBy: 'centres')]
    private Collection $profs;

    /**
     * @var Collection<int, Groupe>
     */
    #[ORM\OneToMany(mappedBy: 'centre', targetEntity: Groupe::class)]
    #[Groups(['centre:read', 'centre:write'])]
    private Collection $groupes;

    public function __construct()
    {
        $this->contrats = new ArrayCollection();
        $this->eleves = new ArrayCollection();
        $this->profs = new ArrayCollection();
        $this->groupes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCentre(): ?string
    {
        return $this->nomCentre;
    }

    public function setNomCentre(string $nomCentre): static
    {
        $this->nomCentre = $nomCentre;
        return $this;
    }

    public function getNbInscrits(): ?int
    {
        return $this->nbInscrits;
    }

    public function setNbInscrits(int $nbInscrits): static
    {
        $this->nbInscrits = $nbInscrits;
        return $this;
    }

    public function getGerant(): ?Gerant
    {
        return $this->gerant;
    }

    public function setGerant(?Gerant $gerant): static
    {
        $this->gerant = $gerant;
        return $this;
    }

    /**
     * @return Collection<int, Contrat>
     */
    public function getContrats(): Collection
    {
        return $this->contrats;
    }

    public function addContrat(Contrat $contrat): static
    {
        if (!$this->contrats->contains($contrat)) {
            $this->contrats->add($contrat);
            $contrat->setCentre($this);
        }

        return $this;
    }

    public function removeContrat(Contrat $contrat): static
    {
        if ($this->contrats->removeElement($contrat)) {
            if ($contrat->getCentre() === $this) {
                $contrat->setCentre(null);
            }
        }

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
            $this->eleves->add($eleve);
            $eleve->setCentre($this);
        }

        return $this;
    }

    public function removeEleve(Eleve $eleve): static
    {
        if ($this->eleves->removeElement($eleve)) {
            if ($eleve->getCentre() === $this) {
                $eleve->setCentre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Prof>
     */
    public function getProfs(): Collection
    {
        return $this->profs;
    }

    public function addProf(Prof $prof): static
    {
        if (!$this->profs->contains($prof)) {
            $this->profs->add($prof);
            $prof->addCentre($this);
        }

        return $this;
    }

    public function removeProf(Prof $prof): static
    {
        if ($this->profs->removeElement($prof)) {
            $prof->removeCentre($this);
        }

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
            $this->groupes->add($groupe);
            $groupe->setCentre($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): static
    {
        if ($this->groupes->removeElement($groupe)) {
            if ($groupe->getCentre() === $this) {
                $groupe->setCentre(null);
            }
        }

        return $this;
    }
}
