<?php

namespace App\Form;

use App\Entity\Rendezvous;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class RendezvousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('daterendezvous' , DateType::class , [
                'widget' => 'single_text',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' =>  date("Y-m-d", time()),
                        'message' =>'Il faut ajouter une date qui commence de ce jour et apres!'

                    ]),
                    new LessThanOrEqual([
                        'value' =>'last day of this month',
                        'message'=>'Vous ne pouvez pas ajouter des dates apres ce mois!'
                        
                    ])

                ],
                
            
            ])
            ->add('heurerendezvous',TimeType::class,[
                'widget' => 'single_text',
                
            ]
     )
            ->add('description')
            ->add('file' , FileType::class , [
                'label' => 'lettre de laison',
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                    ]),
                ],
            ])
                
               

                ->add('Ajouter',SubmitType::class)
            ;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rendezvous::class,
        ]);
    }
}
