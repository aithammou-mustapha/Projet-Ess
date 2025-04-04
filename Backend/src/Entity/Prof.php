<?php

namespace App\Entity;

use App\Repository\ProfRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProfRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['prof:read']],
    denormalizationContext: ['groups' => ['prof:write']]
)]
class Prof
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['prof:read', 'groupe:read', 'centre:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['prof:read', 'prof:write', 'groupe:read'])]
    private ?string $nomProf = null;

    #[ORM\Column(length: 50)]
    #[Groups(['prof:read', 'prof:write', 'groupe:read'])]
    private ?string $prenomProf = null;

    #[ORM\Column(length: 100, unique: true)]
    #[Groups(['prof:read', 'prof:write'])]
    private ?string $emailProf = null;

    #[ORM\Column(length: 20)]
    #[Groups(['prof:read', 'prof:write'])]
    private ?string $telProf = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['prof:read', 'prof:write'])]
    private ?string $disponibilitesProf = null;

    #[ORM\Column(length: 255)]
    #[Groups(['prof:read', 'prof:write'])]
    private ?string $motDePasse = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    #[Groups(['prof:read', 'prof:write'])]
    private ?string $avatarProf = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['prof:read'])]
    private ?\DateTimeImmutable $dateEnregistrementProf = null;

    /**
     * @var Collection<int, Groupe>
     */
    #[ORM\OneToMany(targetEntity: Groupe::class, mappedBy: 'prof', orphanRemoval: true)]
    #[Groups(['prof:read'])]
    private Collection $groupes;

    /**
     * @var Collection<int, Centre>
     */
    #[ORM\ManyToMany(targetEntity: Centre::class, inversedBy: 'profs')]
    #[Groups(['prof:read'])]
    private Collection $centres;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->centres = new ArrayCollection();
        $this->dateEnregistrementProf = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProf(): ?string
    {
        return $this->nomProf;
    }

    public function setNomProf(string $nomProf): static
    {
        $this->nomProf = $nomProf;
        return $this;
    }

    public function getPrenomProf(): ?string
    {
        return $this->prenomProf;
    }

    public function setPrenomProf(string $prenomProf): static
    {
        $this->prenomProf = $prenomProf;
        return $this;
    }

    public function getEmailProf(): ?string
    {
        return $this->emailProf;
    }

    public function setEmailProf(string $emailProf): static
    {
        $this->emailProf = $emailProf;
        return $this;
    }

    public function getTelProf(): ?string
    {
        return $this->telProf;
    }

    public function setTelProf(string $telProf): static
    {
        $this->telProf = $telProf;
        return $this;
    }

    public function getDisponibilitesProf(): ?string
    {
        return $this->disponibilitesProf;
    }

    public function setDisponibilitesProf(?string $disponibilitesProf): static
    {
        $this->disponibilitesProf = $disponibilitesProf;
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

    public function getAvatarProf(): ?string
    {
        return $this->avatarProf;
    }

    public function setAvatarProf(string $avatarProf): static
    {
        $this->avatarProf = $avatarProf;
        return $this;
    }

    public function getDateEnregistrementProf(): ?\DateTimeImmutable
    {
        return $this->dateEnregistrementProf;
    }

    public function setDateEnregistrementProf(\DateTimeImmutable $dateEnregistrementProf): static
    {
        $this->dateEnregistrementProf = $dateEnregistrementProf;
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
            $groupe->setProf($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): static
    {
        if ($this->groupes->removeElement($groupe)) {
            if ($groupe->getProf() === $this) {
                $groupe->setProf(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Centre>
     */
    public function getCentres(): Collection
    {
        return $this->centres;
    }

    public function addCentre(Centre $centre): static
    {
        if (!$this->centres->contains($centre)) {
            $this->centres->add($centre);
        }

        return $this;
    }

    public function removeCentre(Centre $centre): static
    {
        $this->centres->removeElement($centre);
        return $this;
    }
}
