<?php

namespace App\Entity;

use App\Repository\NoteFraisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteFraisRepository::class)]
class NoteFrais
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateDemande = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $raisonDepense = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $totalKm = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $montantKm = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $totalTransport = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $totalAutre = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $totalGeneral = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $montantAbandon = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $montantRembourse = null;

    #[ORM\Column(length: 34, nullable: true)]
    private ?string $iban = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $bic = null;

    #[ORM\Column(length: 30)]
    private ?string $statut = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateCreation = null;

    /**
     * @var Collection<int, LigneFrais>
     */
    #[ORM\OneToMany(targetEntity: LigneFrais::class, mappedBy: 'noteFrais')]
    private Collection $lignesFrais;

    #[ORM\ManyToOne(inversedBy: 'notesFrais')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Budget $budget = null;

    /**
     * @var Collection<int, Justificatif>
     */
    #[ORM\OneToMany(targetEntity: Justificatif::class, mappedBy: 'noteFrais')]
    private Collection $justificatifs;

    #[ORM\Column(length: 255)]
    private ?string $nomDemandeur = null;

    public function __construct()
    {
        $this->lignesFrais = new ArrayCollection();
        $this->justificatifs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDemande(): ?\DateTime
    {
        return $this->dateDemande;
    }

    public function setDateDemande(\DateTime $dateDemande): static
    {
        $this->dateDemande = $dateDemande;

        return $this;
    }

    public function getRaisonDepense(): ?string
    {
        return $this->raisonDepense;
    }

    public function setRaisonDepense(string $raisonDepense): static
    {
        $this->raisonDepense = $raisonDepense;

        return $this;
    }

    public function getTotalKm(): ?string
    {
        return $this->totalKm;
    }

    public function setTotalKm(string $totalKm): static
    {
        $this->totalKm = $totalKm;

        return $this;
    }

    public function getMontantKm(): ?string
    {
        return $this->montantKm;
    }

    public function setMontantKm(string $montantKm): static
    {
        $this->montantKm = $montantKm;

        return $this;
    }

    public function getTotalTransport(): ?string
    {
        return $this->totalTransport;
    }

    public function setTotalTransport(string $totalTransport): static
    {
        $this->totalTransport = $totalTransport;

        return $this;
    }

    public function getTotalAutre(): ?string
    {
        return $this->totalAutre;
    }

    public function setTotalAutre(string $totalAutre): static
    {
        $this->totalAutre = $totalAutre;

        return $this;
    }

    public function getTotalGeneral(): ?string
    {
        return $this->totalGeneral;
    }

    public function setTotalGeneral(string $totalGeneral): static
    {
        $this->totalGeneral = $totalGeneral;

        return $this;
    }

    public function getMontantAbandon(): ?string
    {
        return $this->montantAbandon;
    }

    public function setMontantAbandon(string $montantAbandon): static
    {
        $this->montantAbandon = $montantAbandon;

        return $this;
    }

    public function getMontantRembourse(): ?string
    {
        return $this->montantRembourse;
    }

    public function setMontantRembourse(string $montantRembourse): static
    {
        $this->montantRembourse = $montantRembourse;

        return $this;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(?string $iban): static
    {
        $this->iban = $iban;

        return $this;
    }

    public function getBic(): ?string
    {
        return $this->bic;
    }

    public function setBic(?string $bic): static
    {
        $this->bic = $bic;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

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

    /**
     * @return Collection<int, LigneFrais>
     */
    public function getLignesFrais(): Collection
    {
        return $this->lignesFrais;
    }

    public function addLignesFrais(LigneFrais $ligneFrais): static
    {
        if (!$this->lignesFrais->contains($ligneFrais)) {
            $this->lignesFrais->add($ligneFrais);
            $ligneFrais->setNoteFrais($this);
        }

        return $this;
    }

    public function removeLignesFrais(LigneFrais $ligneFrais): static
    {
        if ($this->lignesFrais->removeElement($ligneFrais)) {
            if ($ligneFrais->getNoteFrais() === $this) {
                $ligneFrais->setNoteFrais(null);
            }
        }

        return $this;
    }

    public function getBudget(): ?Budget
    {
        return $this->budget;
    }

    public function setBudget(?Budget $budget): static
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * @return Collection<int, Justificatif>
     */
    public function getJustificatifs(): Collection
    {
        return $this->justificatifs;
    }

    public function addJustificatif(Justificatif $justificatif): static
    {
        if (!$this->justificatifs->contains($justificatif)) {
            $this->justificatifs->add($justificatif);
            $justificatif->setNoteFrais($this);
        }

        return $this;
    }

    public function removeJustificatif(Justificatif $justificatif): static
    {
        if ($this->justificatifs->removeElement($justificatif)) {
            // set the owning side to null (unless already changed)
            if ($justificatif->getNoteFrais() === $this) {
                $justificatif->setNoteFrais(null);
            }
        }

        return $this;
    }

    public function getNomDemandeur(): ?string
    {
        return $this->nomDemandeur;
    }

    public function setNomDemandeur(string $nomDemandeur): static
    {
        $this->nomDemandeur = $nomDemandeur;

        return $this;
    }
}