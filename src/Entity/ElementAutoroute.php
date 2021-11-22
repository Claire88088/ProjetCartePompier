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
     * @var TypeElementAutoroute
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeElementAutoroute")
     */
    private $typeElementAutoroute;

    /**
     * @return TypeElementAutoroute
     */
    public function getTypeElementAutoroute(): TypeElementAutoroute
    {
        return $this->typeElementAutoroute;
    }

    /**
     * @param TypeElementAutoroute $typeElementAutoroute
     */
    public function setTypeElementAutoroute(TypeElementAutoroute $typeElementAutoroute): void
    {
        $this->typeElementAutoroute = $typeElementAutoroute;
    }

    /**
     * @ORM\Column(type="decimal", precision=10, scale=8)
     */
    private $latitude;

    /**
     * @ORM\Column (type="decimal", precision=10, scale=8)
     */
    private $longitude;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
}
