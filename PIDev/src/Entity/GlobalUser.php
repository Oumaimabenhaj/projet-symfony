<?php

namespace App\Entity;

use App\Repository\GlobalUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: GlobalUserRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name:"Role",type:"string")]
#[ORM\DiscriminatorMap(["Patient","Pharmacien","Admin","Medecin"])]
class GlobalUser 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type:"integer" , unique: true ,nullable: true)]
    #[Assert\NotBlank(message: 'Ce champ est obligatoire !!')]
    #[Assert\Length(
        exactMessage: 'Le CIN doit contenir exactement 8 chiffres.',
        min: 8,
        max: 8
    )]
    #[Assert\Type(type:"integer", message:"Le numéro de CIN doit être un entier.")]
    private ?int $cin = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Ce champ est obligatoire !!')]
    #[Assert\Length(max: 255, maxMessage: 'La longueur maximale est de 255 caractères.')]
    #[Assert\Regex(
    pattern: '/^[a-zA-Z]*$/',
    message: 'Le nom doit contenir uniquement des lettres.'
      )]
    private ?string $nom = null;


    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Ce champ est obligatoire !!')]
    #[Assert\Length(max: 255, maxMessage: 'La longueur maximale est de 255 caractères.')]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]*$/',
        message: 'Le prénom doit contenir uniquement des lettres.'
          )]
    private ?string $prenom = null;

    #[ORM\Column(nullable: true)]
    private ?bool $genre = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\NotBlank(message: 'Ce champ est obligatoire !!')]
    #[Assert\LessThanOrEqual("today", message: 'La date de naissance ne peut pas être dans le futur')]
    #[Assert\GreaterThanOrEqual("-100 years", message: 'La personne doit avoir au maximum 100 ans')]
    private ?\DateTimeInterface $datenaissance = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: 'Ce champ est obligatoire !!')]
    #[Assert\Length(
        exactMessage: 'Le numéro de téléphone doit contenir exactement 8 chiffres.',
        min: 8,
        max: 8
    )]
    #[Assert\Regex(
        pattern: '/^\d+$/',
        message: 'Le numéro de téléphone doit contenir uniquement des chiffres.'
    )]
    private ?int $numtel = null;

    
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Ce champ est obligatoire !!')]
    #[Assert\Length(max: 255, maxMessage: 'La longueur maximale est de 255 caractères.')]
    #[Assert\Email(message: 'L\'adresse e-mail "{{ value }}" n\'est pas valide.')]
    private ?string $email = null;
   
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Ce champ est obligatoire !!')]
    #[Assert\Length(min: 8, minMessage: 'Le mot de passe doit contenir au moins {{ limit }} caractères.')]
    #[Assert\Length(max: 255, maxMessage: 'Le mot de passe ne doit pas dépasser {{ limit }} caractères.' )]
    #[Assert\Regex(
        pattern: '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/',
        message: 'Le mot de passe doit contenir au moins des lettres et des chiffres.'
    )]
    private ?string $password = null;



    #[ORM\Column(nullable: true)]
    private ?bool $interlock = null;

    #[ORM\Column(type:"string", nullable:true)]
    //#[assert\NotNull(message:"image is required")]
    private ?string $image;

    #[ORM\OneToMany(mappedBy: 'id_user', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[ORM\OneToMany(mappedBy: 'id_user', targetEntity: Blog::class)]
    private Collection $blogs;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->blogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(?int $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function isGenre(): ?bool
    {
        return $this->genre;
    }

    public function setGenre(?bool $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getDatenaissance(): ?\DateTimeInterface
    {
        return $this->datenaissance;
    }

    public function setDatenaissance(?\DateTimeInterface $datenaissance): static
    {
        $this->datenaissance = $datenaissance;

        return $this;
    }

    public function getNumtel(): ?int
    {
        return $this->numtel;
    }

    public function setNumtel(?int $numtel): static
    {
        $this->numtel = $numtel;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }
    

    public function getPassword(): ?string
{
    // Chiffrer le mot de passe avec des ***
    //return $this->password ? str_repeat('*', strlen($this->password)) : null;
      return $this->password;
}

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function isInterlock(): ?bool
    {
        return $this->interlock;
    }

    public function setInterlock(?bool $interlock): static
    {
        $this->interlock = $interlock;

        return $this;
    }
    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setIdUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getIdUser() === $this) {
                $commentaire->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Blog>
     */
    public function getBlogs(): Collection
    {
        return $this->blogs;
    }

    public function addBlog(Blog $blog): static
    {
        if (!$this->blogs->contains($blog)) {
            $this->blogs->add($blog);
            $blog->setIdUser($this);
        }

        return $this;
    }

    public function removeBlog(Blog $blog): static
    {
        if ($this->blogs->removeElement($blog)) {
            // set the owning side to null (unless already changed)
            if ($blog->getIdUser() === $this) {
                $blog->setIdUser(null);
            }
        }

        return $this;
    }
    
}

