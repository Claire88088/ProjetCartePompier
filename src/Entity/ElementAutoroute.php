<?php

namespace App\Entity;

use App\Repository\ElementAutorouteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ElementAutorouteRepository::class)
 */
class ElementAutoroute
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
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $coordonneesGPS;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCoordonneesGPS(): ?string
    {
        return $this->coordonneesGPS;
    }

    public function setCoordonneesGPS(string $coordonneesGPS): self
    {
        $this->coordonneesGPS = $coordonneesGPS;

        return $this;
    }
}
