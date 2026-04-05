<?php

namespace App\Entity;

use App\Repository\LigneFraisRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneFraisRepository::class)]
class LigneFrais
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateDepense = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $objetDepense = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $kilometres = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeBareme = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 3, nullable: true)]
    private ?string $tauxKm = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $montantKm = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $montantTransport = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $montantAutre = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $totalLigne = null;

    #[ORM\ManyToOne(inversedBy: 'lignesFrais')]
    #[ORM\JoinColumn(nullable: false)]
    private ?NoteFrais $noteFrais = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDepense(): ?\DateTime
    {
        return $this->dateDepense;
    }

    public function setDateDepense(?\DateTime $dateDepense): static
    {
        $this->dateDepense = $dateDepense;

        return $this;
    }

    public function getObjetDepense(): ?string
    {
        return $this->objetDepense;
    }

    public function setObjetDepense(?string $objetDepense): static
    {
        $this->objetDepense = $objetDepense;

        return $this;
    }

    public function getKilometres(): ?string
    {
        return $this->kilometres;
    }

    public function setKilometres(?string $kilometres): static
    {
        $this->kilometres = $kilometres;

        return $this;
    }

    public function getTypeBareme(): ?string
    {
        return $this->typeBareme;
    }

    public function setTypeBareme(?string $typeBareme): static
    {
        $this->typeBareme = $typeBareme;

        return $this;
    }

    public function getTauxKm(): ?string
    {
        return $this->tauxKm;
    }

    public function setTauxKm(?string $tauxKm): static
    {
        $this->tauxKm = $tauxKm;

        return $this;
    }

    public function getMontantKm(): ?string
    {
        return $this->montantKm;
    }

    public function setMontantKm(?string $montantKm): static
    {
        $this->montantKm = $montantKm;

        return $this;
    }

    public function getMontantTransport(): ?string
    {
        return $this->montantTransport;
    }

    public function setMontantTransport(?string $montantTransport): static
    {
        $this->montantTransport = $montantTransport;

        return $this;
    }

    public function getMontantAutre(): ?string
    {
        return $this->montantAutre;
    }

    public function setMontantAutre(?string $montantAutre): static
    {
        $this->montantAutre = $montantAutre;

        return $this;
    }

    public function getTotalLigne(): ?string
    {
        return $this->totalLigne;
    }

    public function setTotalLigne(?string $totalLigne): static
    {
        $this->totalLigne = $totalLigne;

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
