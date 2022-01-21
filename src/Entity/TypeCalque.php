<?php

namespace App\Entity;

use App\Repository\TypeCalqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeCalqueRepository::class)
 */
class TypeCalque
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
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\TypeElement", mappedBy="typeCalque")
     */
    private $typesElement;

    public function __construct()
    {
        $this->typesElement = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
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
        $calqueToString = $this->nom;
        return $calqueToString;
    }

    /**
     * @return Collection
     */
    public function getTypesElement()
    {
        return $this->typesElement;
    }

    /**
     * @param TypeElement $typeElement
     * @return self
     */
    public function addTypeElement(TypeElement $typeElement): self
    {
        if (!$this->typesElement->contains($typeElement)) {
            $this->typesElement->add($typeElement);
            $typeElement->setTypeCalque($this);
        }
        return $this;
    }

    /**
     * @param TypeElement $typeElement
     * @return self
     */
    public function removeTypeElement(TypeElement $typeElement): self
    {
        if ($this->typesElement->removeElement($typeElement)) {
            if ($typeElement->getTypeCalque() === $this) {
                $typeElement->setTypeCalque(null);
            }
        }
        return $this;
    }

}
