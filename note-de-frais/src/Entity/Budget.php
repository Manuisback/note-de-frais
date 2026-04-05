<?php

namespace App\Entity;

use App\Repository\BudgetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BudgetRepository::class)]
class Budget
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $montantInitial = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $montantRestant = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateCreation = null;

    /**
     * @var Collection<int, NoteFrais>
     */
    #[ORM\OneToMany(targetEntity: NoteFrais::class, mappedBy: 'budget')]
    private Collection $notesFrais;

    public function __construct()
    {
        $this->notesFrais = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMontantInitial(): ?string
    {
        return $this->montantInitial;
    }

    public function setMontantInitial(string $montantInitial): static
    {
        $this->montantInitial = $montantInitial;

        return $this;
    }

    public function getMontantRestant(): ?string
    {
        return $this->montantRestant;
    }

    public function setMontantRestant(string $montantRestant): static
    {
        $this->montantRestant = $montantRestant;

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
     * @return Collection<int, NoteFrais>
     */
    public function getNotesFrais(): Collection
    {
        return $this->notesFrais;
    }

    public function addNotesFrai(NoteFrais $notesFrai): static
    {
        if (!$this->notesFrais->contains($notesFrai)) {
            $this->notesFrais->add($notesFrai);
            $notesFrai->setBudget($this);
        }

        return $this;
    }

    public function removeNotesFrai(NoteFrais $notesFrai): static
    {
        if ($this->notesFrais->removeElement($notesFrai)) {
            if ($notesFrai->getBudget() === $this) {
                $notesFrai->setBudget(null);
            }
        }

        return $this;
    }
}