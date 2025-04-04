<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalleRepository::class)]
class Salle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $numSalle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $disponibilitesSalle = null;

    #[ORM\Column]
    private ?int $capaciteSalle = null;

    /**
     * @var Collection<int, Groupe>
     */
    #[ORM\OneToMany(targetEntity: Groupe::class, mappedBy: 'salle', orphanRemoval: true)]
    private Collection $groupes;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumSalle(): ?string
    {
        return $this->numSalle;
    }

    public function setNumSalle(string $numSalle): static
    {
        $this->numSalle = $numSalle;

        return $this;
    }

    public function getDisponibilitesSalle(): ?string
    {
        return $this->disponibilitesSalle;
    }

    public function setDisponibilitesSalle(?string $disponibilitesSalle): static
    {
        $this->disponibilitesSalle = $disponibilitesSalle;

        return $this;
    }

    public function getCapaciteSalle(): ?int
    {
        return $this->capaciteSalle;
    }

    public function setCapaciteSalle(int $capaciteSalle): static
    {
        $this->capaciteSalle = $capaciteSalle;

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
            $groupe->setSalle($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): static
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getSalle() === $this) {
                $groupe->setSalle(null);
            }
        }

        return $this;
    }
}
