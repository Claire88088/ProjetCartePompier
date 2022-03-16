<?php

namespace App\Entity;

use App\Repository\ElementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ElementRepository::class)
 * @UniqueEntity(
 *     fields="nom",
 *     message="Ce nom est déjà utilisé"
 *     )
 */
class Element
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Le nom de l'élément est obligatoire")
     * @Assert\Type("string")
     * @Assert\Length(max="50")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $texte;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lien;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDeb;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateFin;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeElement", inversedBy="elements")
     */
    private $typeElement;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Icone", inversedBy="elements")
     */
    private $icone;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $couleur;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Point", mappedBy="element", cascade={"persist","remove"})
     */
    private $points;

    public function __construct()
    {
        $this->points = new ArrayCollection();
    }

    /**
     * @return TypeElement
     */
    public function getTypeElement(): ?TypeElement
    {
        return $this->typeElement;
    }

    /**
     * @param TypeElement $typeElement
     * @return Element
     */
    public function setTypeElement(TypeElement $typeElement)
    {
        if (!$typeElement->getElements()->contains($this)) {
            $this->typeElement = $typeElement;
            $typeElement->addElement($this);
        }
        return $this;
    }

    /**
     * @return Icone
     */
    public function getIcone(): ?Icone
    {
        return $this->icone;
    }

    /**
     * @param Icone $icone
     * @return Element
     */
    public function setIcone(Icone $icone)
    {
        if (!$icone->getElements()->contains($this)) {
            $this->icone = $icone;
            $icone->addElement($this);
        }
        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(?string $texte): self
    {
        $this->texte = $texte;

        return $this;
    }

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(?string $lien): self
    {
        $this->lien = $lien;

        return $this;
    }

    public function getDateDeb(): ?\DateTimeInterface
    {
        return $this->dateDeb;
    }

    public function setDateDeb(?\DateTimeInterface $dateDeb): self
    {
        $this->dateDeb = $dateDeb;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getCouleur()
    {
        return $this->couleur;
    }

    public function setCouleur($couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getPoints(): ?Collection
    {
        return $this->points;
    }

    /**
     * @param Point $point
     * @return self
     */
    public function addPoint(Point $point): self
    {
        if (!$this->points->contains($point)) {
            $this->points->add($point);
            $point->setElement($this);
        }
        return $this;
    }

    /**
     * @param Point $point
     * @return self
     */
    public function removePoint(Point $point): self
    {
        if ($this->points->removeElement($point)) {
            if ($point->getElement() === $this) {
                $point->setElement(null);
            }
        }
        return $this;
    }
}
