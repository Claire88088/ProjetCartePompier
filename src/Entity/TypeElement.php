<?php

namespace App\Entity;

use App\Repository\TypeElementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeElementRepository::class)
 */
class TypeElement
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeCalque", inversedBy="typesElement")
     */
    private $typeCalque;

    /**
     * @return mixed
     */
    public function getTypeCalque()
    {
        return $this->typeCalque;
    }

    /**
     * @param mixed $typeCalque
     * @return self
     */
    public function setTypeCalque($typeCalque)
    {
        if (!$typeCalque->getTypesElement()->contains($this)) {
            $this->typeCalque = $typeCalque;
            $typeCalque->addTypeElement($this);
        }
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
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

    public function __toString()
    {
        return $this->nom;
    }
}
