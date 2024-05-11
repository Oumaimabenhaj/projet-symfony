<?php

namespace App\Form;

use App\Entity\GlobalUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Email'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir votre adresse email']),
                    new Email(['message' => 'L\'adresse email "{{ value }}" n\'est pas valide.']),
                ],
                'error_bubbling' => false,
            ])
            ->add('password', PasswordType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Password', 'id' => 'password-input'],
                'error_bubbling' => false,
            ])
            ->add('Submit',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GlobalUser::class,
        ]);
    }
}
