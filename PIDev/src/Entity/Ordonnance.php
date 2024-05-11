<?php

namespace App\Entity;

use App\Repository\OrdonnanceRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\ORM\Mapping as ORM;

use DateTime;
#[ORM\Entity(repositoryClass: OrdonnanceRepository::class)]
class Ordonnance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;
    

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'Ce champ est obligatoire !')]
    private ?\DateTimeInterface $renouvellement = null;
      /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->dateprescription > $this->renouvellement) {
            $context->buildViolation('La date de renouvellement doit être postérieure à la date de prescription')
                ->atPath('renouvellement')
                ->addViolation();
        }
    }

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 10, minMessage: "Votre description doit contenir au moins {{ limit }} caractères.")]
    #[Assert\NotBlank(message:"Champs obligatoire !")]
    private ?string $medecamentprescrit = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Ce champ est obligatoire !')]
    #[Assert\NotBlank(message:"Champs obligatoire")]
    private ?string $adresse = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'Ce champ est obligatoire !')]
    private ?\DateTimeInterface $dateprescription = null;

    #[ORM\ManyToOne(inversedBy: 'ordonnance')]
    private ?Dossiermedical $dossiermedical = null;

    #[ORM\ManyToOne(inversedBy: 'ordonnances')]
    private ?Patient $idpatient = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Ce champ est obligatoire !')]
    #[Assert\Length(max: 255, maxMessage: 'La longueur maximale est de 255 caractères.')]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]*$/',
        message: 'Le nom doit contenir uniquement des lettres.'
          )]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Ce champ est obligatoire !')]
    #[Assert\Length(max: 255, maxMessage: 'La longueur maximale est de 255 caractères.')]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]*$/',
        message: 'Le prénom doit contenir uniquement des lettres.'
          )]
    private ?string $prenom = null;

    
    

    public function getRenouvellement(): ?\DateTimeInterface
    {
        return $this->renouvellement;
    }

    public function setRenouvellement(\DateTimeInterface $renouvellement): static
    {
        $this->renouvellement = $renouvellement;

        return $this;
    }

    public function getMedecamentprescrit(): ?string
    {
        return $this->medecamentprescrit;
    }

    public function setMedecamentprescrit(string $medecamentprescrit): static
    {
        $this->medecamentprescrit = $medecamentprescrit;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getDateprescription(): ?\DateTimeInterface
    {
        return $this->dateprescription;
    }

    public function setDateprescription( $dateprescription): static
    {
        $this->dateprescription = $dateprescription;

        return $this;
    }

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDossiermedical(): ?Dossiermedical
    {
        return $this->dossiermedical;
    }

    public function setDossiermedical(?Dossiermedical $dossiermedical): static
    {
        $this->dossiermedical = $dossiermedical;

        return $this;
    }

    public function getIdpatient(): ?Patient
    {
        return $this->idpatient;
    }

    public function setIdpatient(?Patient $idpatient): static
    {
        $this->idpatient = $idpatient;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

   

   

   

}
