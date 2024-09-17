<?php

namespace App\Entity;

use App\Repository\TaillerRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Haie;

#[ORM\Entity(repositoryClass: TaillerRepository::class)]
class Tailler
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'taillers')]
    #[ORM\JoinColumn(name: 'id_devis_id', referencedColumnName: 'id')]
    private ?Devis $devis = null;

    #[ORM\Column(type: "float")]
    private ?float $hauteur = null;

    #[ORM\Column(type: "float")]
    private ?float $longueur = null;

    #[ORM\ManyToOne(targetEntity: "Haie", inversedBy: "taillers")]
    #[ORM\JoinColumn(name: "haie_id", referencedColumnName: "id")]
     private ?Haie $haie = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDevis(): ?devis
    {
        return $this->devis ;
    }

    public function setDevis(?devis $devis): static
    {
        $this->devis  = $devis;

        return $this;
    }



    public function getHauteur(): ?float
    {
        return $this->hauteur;
    }

    public function setHauteur(float $hauteur): static
    {
        $this->hauteur = $hauteur;

        return $this;
    }

    public function getLongueur(): ?float
    {
        return $this->longueur;
    }

    public function setLongueur(float $longueur): static
    {
        $this->longueur = $longueur;

        return $this;
    }


    public function getHaie(): ?haie
    {
        return $this->haie;
    }

    public function setHaie(?Haie $haie): static
    {
        $this->haie = $haie;
        return $this;
    }
}
