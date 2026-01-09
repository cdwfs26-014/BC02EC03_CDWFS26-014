<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvisRepository::class)]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $note = null;

    #[ORM\Column]
    private ?\DateTime $creer_le = null;

    #[ORM\Column]
    private ?\DateTime $modifier_le = null;

    #[ORM\Column]
    private ?bool $accepter = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'avis')]
    private ?Utilisateur $a_U = null;

    #[ORM\ManyToOne(inversedBy: 'a_E')]
    private ?Evenement $evenement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getCreerLe(): ?\DateTime
    {
        return $this->creer_le;
    }

    public function setCreerLe(\DateTime $creer_le): static
    {
        $this->creer_le = $creer_le;

        return $this;
    }

    public function getModifierLe(): ?\DateTime
    {
        return $this->modifier_le;
    }

    public function setModifierLe(\DateTime $modifier_le): static
    {
        $this->modifier_le = $modifier_le;

        return $this;
    }

    public function isAccepter(): ?bool
    {
        return $this->accepter;
    }

    public function setAccepter(bool $accepter): static
    {
        $this->accepter = $accepter;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getAU(): ?Utilisateur
    {
        return $this->a_U;
    }

    public function setAU(?Utilisateur $a_U): static
    {
        $this->a_U = $a_U;

        return $this;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): static
    {
        $this->evenement = $evenement;

        return $this;
    }
}
