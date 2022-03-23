<?php

namespace App\Entity;

use App\Repository\IconeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=IconeRepository::class)
 * @UniqueEntity(
 *     fields={"nom", "unicode"},
 *     message="Cette valeur est déjà utilisée"
 *     )
 */
class Icone
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     * @Assert\NotBlank(message="Le nom de l'icône est obligatoire")
     * @Assert\Type("string")
     * @Assert\Length(max="50")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="L'unicode de l'icône est obligatoire")
     * @Assert\Type("string")
     * @Assert\Length(max="10")
     * @Assert\Regex(
     *     pattern="/^E[0-9]+/",
     *     match=true,
     *     message="L'unicode doit être par exemple : E800"
     * )
     */
    private $unicode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lien;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Element", mappedBy="icone")
     */
    private $elements;

    public function __construct()
    {
        $this->elements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    public function getUnicode(): ?string
    {
        return $this->unicode;
    }

    public function setUnicode(?string $unicode): self
    {
        $this->unicode = $unicode;

        return $this;
    }

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(string $lien): self
    {
        $this->lien = $lien;

        return $this;
    }

    public function __toString()
    {
        return strip_tags($this->lien);
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
            $element->setIcone($this);
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
            if ($element->getIcone() === $this) {
                $element->setIcone(null);
            }
        }
        return $this;
    }
}
