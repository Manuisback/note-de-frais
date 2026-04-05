<?php

namespace App\Entity;

use App\Repository\JustificatifRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JustificatifRepository::class)]
class Justificatif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomFichier = null;

    #[ORM\Column(length: 255)]
    private ?string $cheminFichier = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateCreation = null;

    #[ORM\ManyToOne(inversedBy: 'justificatifs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?NoteFrais $noteFrais = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFichier(): ?string
    {
        return $this->nomFichier;
    }

    public function setNomFichier(string $nomFichier): static
    {
        $this->nomFichier = $nomFichier;

        return $this;
    }

    public function getCheminFichier(): ?string
    {
        return $this->cheminFichier;
    }

    public function setCheminFichier(string $cheminFichier): static
    {
        $this->cheminFichier = $cheminFichier;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeImmutable
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeImmutable $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getNoteFrais(): ?NoteFrais
    {
        return $this->noteFrais;
    }

    public function setNoteFrais(?NoteFrais $noteFrais): static
    {
        $this->noteFrais = $noteFrais;

        return $this;
    }
}
