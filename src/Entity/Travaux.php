<?php

namespace App\Entity;

use App\Repository\TravauxRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TravauxRepository::class)
 */
class Travaux
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Calque
     * @ORM\ManyToOne(targetEntity="App\Entity\Calque")
     */
    private $calque;

    /**
     * @return Calque
     */
    public function getCalque(): Calque
    {
        return $this->calque;
    }

    /**
     * @param Calque $calque
     */
    public function setCalque(Calque $calque): void
    {
        $this->calque = $calque;
    }

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateFin;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresseDebut;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresseFin;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getAdresseDebut(): ?string
    {
        return $this->adresseDebut;
    }

    public function setAdresseDebut(string $adresseDebut): self
    {
        $this->adresseDebut = $adresseDebut;

        return $this;
    }

    public function getAdresseFin(): ?string
    {
        return $this->adresseFin;
    }

    public function setAdresseFin(string $adresseFin): self
    {
        $this->adresseFin = $adresseFin;

        return $this;
    }
}
