<?php

namespace App\Entity;

use App\Repository\TypeElementAutorouteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeElementAutorouteRepository::class)
 */
class TypeElementAutoroute
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomType(): ?string
    {
        return $this->nomType;
    }

    public function setNomType(string $nomType): self
    {
        $this->nomType = $nomType;

        return $this;
    }
}
