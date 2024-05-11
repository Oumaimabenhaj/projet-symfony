<?php

namespace App\Form;

use App\Entity\Admin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cin')
            ->add('nom')
            ->add('prenom')
            ->add('genre', ChoiceType::class, [
                'choices' => [
                    'Femme' => 'F',
                    'Homme' => 'H',
                ],
                'expanded' => true, // Afficher les choix comme des boutons radio
                'multiple' => false, // Permettre la sélection d'un seul choix
            ])
           
            ->add('datenaissance', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'constraints' => [
                    new Assert\Callback([$this, 'validateAge']),
                ],
            ])
            ->add('numtel')
            ->add('email')
            ->add('password', PasswordType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Password', 'id' => 'password-input'],
            ])
            ->add('interlock', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Interlock', // Ajoutez une étiquette pour le champ interlock
            ])
            ->add('image', FileType::class, [
                'label' => 'admin Image',
                'required' => false,
                'mapped' => false, // Do not map this field to the entity property
            ])
            ->add('Ajouter', SubmitType::class)
            ->add('Annuler', SubmitType::class);
    }
    

    


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Admin::class,
        ]);
    }
    public function validateAge($value, ExecutionContextInterface $context)
{
    // Récupérer la date de naissance
    $datenaissance = $value;

    // Calculer l'âge
    $now = new \DateTime();
    $age = $now->diff($datenaissance)->y;

    // Vérifier si l'âge est inférieur à 23 ans
    if ($age < 23) {
        $context->buildViolation('Vous devez avoir au moins 23 ans.')
            ->atPath('datenaissance')
            ->addViolation();
    }
}
}