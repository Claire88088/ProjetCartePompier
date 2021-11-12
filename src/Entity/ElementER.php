<?php

namespace App\Entity;

use App\Repository\ElementERRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ElementERRepository::class)
 */
class ElementER
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var EtablissementRepertorie
     * @ORM\ManyToOne(targetEntity="App\Entity\EtablissementRepertorie")
     */
    private $etablissementRepertorie;

    /**
     * @return EtablissementRepertorie
     */
    public function getEtablissementRepertorie(): EtablissementRepertorie
    {
        return $this->etablissementRepertorie;
    }

    /**
     * @param EtablissementRepertorie $etablissementRepertorie
     */
    public function setEtablissementRepertorie(EtablissementRepertorie $etablissementRepertorie): void
    {
        $this->etablissementRepertorie = $etablissementRepertorie;
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
