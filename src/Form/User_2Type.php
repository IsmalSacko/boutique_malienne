<?php

namespace App\Form;

use App\Entity\User_2;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class User_2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['placeholder'=>'Tapez votre Prénom'],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder'=>'Tapez votre nom'],
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'attr' => ['placeholder'=>'Tapez votre email'],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message'=> 'Le mot de passe et la confirmation doivent être identiques !',
                'first_options' => ['label'=>'Mot de passe',
                    'attr' => ['placeholder'=>'Tapez votre mot de passe'],
                    ],
                'second_options' => ['label'=>'Comfirmez le mot de passe',
                    'attr' => ['placeholder'=>'Comfirmez le mot de passe'],
                    ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User_2::class,
        ]);
    }
}
