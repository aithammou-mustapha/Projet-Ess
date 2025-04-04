<?php

namespace App\Entity;

use App\Repository\GerantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: GerantRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL_GERANT', fields: ['emailGerant'])]
class Gerant implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $emailGerant = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $motDePasse = null;

    #[ORM\Column(length: 50)]
    private ?string $nomGerant = null;

    #[ORM\Column(length: 50)]
    private ?string $prenomGerant = null;

    #[ORM\Column(length: 20)]
    private ?string $telGerant = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)] //NULL
    private ?string $avatarGerant = null;

    /**
     * @var Collection<int, Centre>
     */
    #[ORM\OneToMany(targetEntity: Centre::class, mappedBy: 'gerant', orphanRemoval: false)]
    private Collection $centres;

    public function __construct()
    {
        $this->centres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmailGerant(): ?string
    {
        return $this->emailGerant;
    }

    public function setEmailGerant(string $emailGerant): static
    {
        $this->emailGerant = $emailGerant;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->emailGerant;
    }

    /**
     * @see UserInterface
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->motDePasse;
    }

    public function setPassword(string $motDePasse): static
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNomGerant(): ?string
    {
        return $this->nomGerant;
    }

    public function setNomGerant(string $nomGerant): static
    {
        $this->nomGerant = $nomGerant;

        return $this;
    }

    public function getPrenomGerant(): ?string
    {
        return $this->prenomGerant;
    }

    public function setPrenomGerant(string $prenomGerant): static
    {
        $this->prenomGerant = $prenomGerant;

        return $this;
    }

    public function getTelGerant(): ?string
    {
        return $this->telGerant;
    }

    public function setTelGerant(string $telGerant): static
    {
        $this->telGerant = $telGerant;

        return $this;
    }

    public function getAvatarGerant(): ?string
    {
        return $this->avatarGerant;
    }

    public function setAvatarGerant(string $avatarGerant): static
    {
        $this->avatarGerant = $avatarGerant;

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
            $centre->setGerant($this);
        }

        return $this;
    }

    public function removeCentre(Centre $centre): static
    {
        if ($this->centres->removeElement($centre)) {
            // set the owning side to null (unless already changed)
            if ($centre->getGerant() === $this) {
                $centre->setGerant(null);
            }
        }

        return $this;
    }
}
