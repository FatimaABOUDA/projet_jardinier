<?php

namespace App\Entity;

use App\Repository\HaieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HaieRepository::class)]
class Haie
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: "float")]
    private ?float $prix = null;

    #[ORM\ManyToOne(inversedBy: 'haies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    #[ORM\OneToMany(mappedBy: 'idHaie', targetEntity: Tailler::class)]
    private Collection $taillers;


    public function __construct()
    {
        $this->taillers = new ArrayCollection();
        $this->idTaillers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Tailler>
     */
    public function getTaillers(): Collection
    {
        return $this->taillers;
    }

    public function addTailler(Tailler $tailler): static
    {
        if (!$this->taillers->contains($tailler)) {
            $this->taillers->add($tailler);
            $tailler->setHaie($this);
        }
        return $this;
    }

    public function removeTailler(Tailler $tailler): static
    {
        if ($this->taillers->removeElement($tailler)) {
            // set the owning side to null (unless already changed)
            $tailler->setHaie(null);
        }
        return $this;
    }


    /**
     * @return Collection<int, Tailler>
     */
    public function getIdTaillers(): Collection
    {
        return $this->idTaillers;
    }

    public function addIdTailler(Tailler $idTailler): static
    {
        if (!$this->idTaillers->contains($idTailler)) {
            $this->idTaillers->add($idTailler);
            $idTailler->setCode($this);
        }

        return $this;
    }

    public function removeIdTailler(Tailler $idTailler): static
    {
        if ($this->idTaillers->removeElement($idTailler)) {
            // set the owning side to null (unless already changed)
            if ($idTailler->getCode() === $this) {
                $idTailler->setCode(null);
            }
        }

        return $this;
    }
}
