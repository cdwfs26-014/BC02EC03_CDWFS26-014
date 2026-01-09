<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    /**
     * @var Collection<int, Avis>
     */
    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'evenement')]
    private Collection $a_E;

    /**
     * @var Collection<int, Utilisateur>
     */
    #[ORM\ManyToMany(targetEntity: Utilisateur::class, inversedBy: 'evenements')]
    private Collection $Responsable;

    public function __construct()
    {
        $this->a_E = new ArrayCollection();
        $this->Responsable = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getAE(): Collection
    {
        return $this->a_E;
    }

    public function addAE(Avis $aE): static
    {
        if (!$this->a_E->contains($aE)) {
            $this->a_E->add($aE);
            $aE->setEvenement($this);
        }

        return $this;
    }

    public function removeAE(Avis $aE): static
    {
        if ($this->a_E->removeElement($aE)) {
            // set the owning side to null (unless already changed)
            if ($aE->getEvenement() === $this) {
                $aE->setEvenement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getResponsable(): Collection
    {
        return $this->Responsable;
    }

    public function addResponsable(Utilisateur $responsable): static
    {
        if (!$this->Responsable->contains($responsable)) {
            $this->Responsable->add($responsable);
        }

        return $this;
    }

    public function removeResponsable(Utilisateur $responsable): static
    {
        $this->Responsable->removeElement($responsable);

        return $this;
    }
}
