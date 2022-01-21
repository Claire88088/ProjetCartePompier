<?php

namespace App\Entity;

use App\Repository\TypeElementRepository;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Element", mappedBy="typeElement")
     */
    private $elements;

    public function __construct()
    {
        $this->elements = new ArrayCollection();
    }

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

    public function setId(int $id): self
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
        return $this->nom;
    }

    /**
     * @return Collection
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * @param Element $element
     * @return self
     */
    public function addElement(Element $element): self
    {
        if (!$this->elements->contains($element)) {
            $this->elements->add($element);
            $element->setTypeElement($this);
        }
        return $this;
    }

    /**
     * @param Element $element
     * @return self
     */
    public function removeElement(Element $element): self
    {
        if ($this->elements->removeElement($element)) {
            if ($element->getTypeElement() === $this) {
                $element->setTypeElement(null);
            }
        }
        return $this;
    }
}
