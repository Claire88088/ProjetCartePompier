<?php

namespace App\Entity;

use App\Repository\TypeCalqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=TypeCalqueRepository::class)
 * @UniqueEntity(
 *     fields="nom",
 *     message="Ce nom est déjà utilisé"
 *     )
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
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Le nom du calque est obligatoire")
     * @Assert\Type("string")
     * @Assert\Length(max="50")
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
        return strip_tags($this->nom);
    }

    /**
     * @return Collection
     */
    public function getTypesElement(): ?Collection
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
