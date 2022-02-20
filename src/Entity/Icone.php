<?php

namespace App\Entity;

use App\Repository\IconeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IconeRepository::class)
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
     * @ORM\Column(type="string", length=255, nullable=true)
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
