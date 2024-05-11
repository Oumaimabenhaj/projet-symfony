<?php

namespace App\Form;

use App\Entity\Patient;
use App\Entity\Ordonnance;
use App\Entity\Dossiermedical;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdonnanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('nom')
            ->add('prenom')
            ->add('medecamentprescrit')
            ->add('adresse')
            ->add('renouvellement')
            ->add('dateprescription')
            // ->add('patient',EntityType::class,[
            //     'class'=>Patient::class,
            //     'choice_label'=>'nom'
            // ])
             /*->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => 'nom', // Nom de la propriété de l'entité Patient à afficher dans le champ
               'placeholder' => 'Sélectionner un patient', // Optionnel : texte à afficher comme option vide
              // Vous pouvez ajouter d'autres options selon vos besoins
             ])*/
            //->add('idpatient')
            ->add('Dossiermedical', EntityType::class, [
                'class' => Dossiermedical::class,
                'choice_label' => function ($dossiermedical) {
                    // Retourner ici la  représentation que vous souhaitez utiliser pour chaque élément dans la liste déroulante
                    return $dossiermedical->getId();
                },
                'multiple' => false, // Permettre la sélection de plusieurs dossiers médicaux
                'expanded' => false, // Afficher la liste déroulante sous forme de liste
                'required' => false,
                'by_reference' => false, // Important pour que les changements soient bien suivis par Symfony
                // Autres options de configuration selon vos besoins
            ])
            ->add('Ajouter', SubmitType::class)
        ;
    }
            

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ordonnance::class,
            
        ]);
    }
}
