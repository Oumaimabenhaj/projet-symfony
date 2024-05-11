<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient extends GlobalUser 
{

    
    #[ORM\Column(type:"integer" , unique: true ,nullable: true)]
    #[Assert\NotBlank(message: 'Ce champ est obligatoire !!')]
    #[Assert\Length(
        exactMessage: 'Le numéro de lacarte jaune doit contenir exactement 5 chiffres.',
        min: 5,
        max: 5
    )]
    #[Assert\Type(type:"integer", message:"Le numéro de la carte jaune doit être un entier.")]
    private ?int $numcarte = null;

    #[ORM\OneToMany(mappedBy: 'idpatient', targetEntity: Ordonnance::class)]
    private Collection $ordonnances;

    #[ORM\OneToMany(mappedBy: 'idpatient', targetEntity: Rendezvous::class)]
    private Collection $rendezvouses;

    public function __construct()
    {
        $this->ordonnances = new ArrayCollection();
        $this->rendezvouses = new ArrayCollection();
    }

    public function getNumcarte(): ?int
    {
        return $this->numcarte;
    }

    public function setNumcarte(?int $numcarte): static
    {
        $this->numcarte = $numcarte;

        return $this;
    }

    /**
     * @return Collection<int, Ordonnance>
     */
    public function getOrdonnances(): Collection
    {
        return $this->ordonnances;
    }

    public function addOrdonnance(Ordonnance $ordonnance): static
    {
        if (!$this->ordonnances->contains($ordonnance)) {
            $this->ordonnances->add($ordonnance);
            $ordonnance->setIdpatient($this);
        }

        return $this;
    }

    public function removeOrdonnance(Ordonnance $ordonnance): static
    {
        if ($this->ordonnances->removeElement($ordonnance)) {
            // set the owning side to null (unless already changed)
            if ($ordonnance->getIdpatient() === $this) {
                $ordonnance->setIdpatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rendezvous>
     */
    public function getRendezvouses(): Collection
    {
        return $this->rendezvouses;
    }

    public function addRendezvouse(Rendezvous $rendezvouse): static
    {
        if (!$this->rendezvouses->contains($rendezvouse)) {
            $this->rendezvouses->add($rendezvouse);
            $rendezvouse->setIdpatient($this);
        }

        return $this;
    }

    public function removeRendezvouse(Rendezvous $rendezvouse): static
    {
        if ($this->rendezvouses->removeElement($rendezvouse)) {
            // set the owning side to null (unless already changed)
            if ($rendezvouse->getIdpatient() === $this) {
                $rendezvouse->setIdpatient(null);
            }
        }

        return $this;
    }

   
}
