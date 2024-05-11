<?php

namespace App\Form;

use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Ordonnance;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class DossierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
       // ->add('ordonnance', EntityType::class, [
          //  'class' => Ordonnance::class,
           //'choice_label' => 'id', 
           // 'multiple' => true, // Pour permettre la sélection de plusieurs ordonnances
           // 'expanded' => false, // Afficher la liste déroulante sous forme de liste
           // 'required' => false, 
           // 'by_reference' => false, // Important pour que les changements soient bien suivis par Symfony
            // //Autres options de configuration selon vos besoins
      // ])

        ->add('nom')
        ->add('prenom')
        ->add('DateCreation')
        ->add('resultatexamen')
        //->add('numdossier')
        ->add('image', FileType::class, [
            'label' => 'Dossiermedical Image',
            'required' => false,
            'mapped' => false, // Do not map this field to the entity property
        ])
      //// ->add('patient', EntityType::class, [
         //////'class' => Patient::class,
         ///'choice_label' => 'id',
        /// 'placeholder' => 'Sélectionnez un patient',])
        ->add('antecedentspersonnelles', ChoiceType::class, [
            'choices' => [
                'Hypertension' => 'hypertension artérielle',
                'Diabète' => 'diabete', 
                'Maladies cardiaques' => 'maladies_cardiaques', 
                'Allergies aux pénicillines' => 'allergies_penicillines', 
            ],
            'multiple' => true, // Pour le choix multiple
            'expanded' => true, // Afficher les choix sous forme de cases à cocher
        ])
        
        
        ->add('Ajouter', SubmitType::class);
      
    
    

        
        

            
        
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
