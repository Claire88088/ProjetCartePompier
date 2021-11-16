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
     * @ORM\Column(type="decimal", precision=10, scale=8)
     */
    private $latitudeDebut;

    /**
     * @ORM\Column (type="decimal", precision=10, scale=8)
     */
    private $longitudeDebut;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=8)
     */
    private $latitudeFin;

    /**
     * @ORM\Column (type="decimal", precision=10, scale=8)
     */
    private $longitudeFin;

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

    public function getLatitudeDebut(): ?string
    {
        return $this->latitudeDebut;
    }

    public function setLatitudeDebut(string $latitudeDebut): self
    {
        $this->latitudeDebut = $latitudeDebut;

        return $this;
    }

    public function getLongitudeDebut(): ?string
    {
        return $this->longitudeDebut;
    }

    public function setLongitudeDebut(string $longitudeDebut): self
    {
        $this->longitudeDebut = $longitudeDebut;

        return $this;
    }

    public function getLatitudeFin(): ?string
    {
        return $this->latitudeFin;
    }

    public function setLatitudeFin(string $latitudeFin): self
    {
        $this->latitudeDebut = $latitudeFin;

        return $this;
    }

    public function getLongitudeFin(): ?string
    {
        return $this->longitudeFin;
    }

    public function setLongitudeFin(string $longitudeFin): self
    {
        $this->longitudeDebut = $longitudeFin;

        return $this;
    }
}
