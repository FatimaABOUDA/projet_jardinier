<?php
namespace App\Entity;

use App\Repository\DevisRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: DevisRepository::class)]
class Devis
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type:"datetime")]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    private ?User $user = null;

#[ORM\Column(type: 'float', nullable: true)]
    private ?float $montantTotal = null; // Nouvelle propriété pour le montant total

    public function getMontantTotal(): ?float
    {
        return $this->montantTotal;
    }

    public function setMontantTotal(float $montantTotal): static
    {
        $this->montantTotal = $montantTotal;
        return $this;
    }

    #[ORM\OneToMany(mappedBy: 'devis', targetEntity: Tailler::class)]
    private Collection $taillers;

    #[ORM\Column(length: 255)]
    private ?string $typeUtilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNo(): ?string
    {
        return $this->no;
    }

    public function setNo(string $no): static
    {
        $this->no = $no;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTaillers()
    {
        return $this->taillers;
    }

    public function setTaillers($taillers)
    {
        $this->taillers = $taillers;
        return $this;
    }

    public function getTypeUtilisateur(): ?string
    {
        return $this->typeUtilisateur;
    }

    public function setTypeUtilisateur(string $typeUtilisateur): self
    {
        $this->typeUtilisateur = $typeUtilisateur;

        return $this;
    }
}
