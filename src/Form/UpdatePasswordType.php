<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdatePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class,[
                'disabled' =>true,
                'label' => 'Prénom'
                ])
            ->add('lastname',TextType::class,[
                'disabled' =>true,
                'label' => 'Nom'
            ])
            ->add('email',TextType::class,[
                'disabled' =>true,
            ])
            ->add('old_password',PasswordType::class,[
                'mapped' =>false,
                'invalid_message'=> 'Le mot de passe et la confirmation doivent être identiques !',
                'label' => 'Mot de passe actuel',

            ])->add('new_password',RepeatedType::class,[
                'type' => PasswordType::class,
                'invalid_message'=> 'Le mot de passe et la confirmation doivent être identiques !',
                'label' => 'Mot de passe actuel',
                'mapped' => false,
                'required' => true,
                'first_options' => ['label' => 'Mon nouveau mot de passe'],
                'second_options' => ['label' => 'Confirmez le nouveau mot de passe'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
