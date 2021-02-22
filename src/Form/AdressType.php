<?php

namespace App\Form;

use App\Entity\Adress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Quel nom souhaiterez-vous donner à votre adresse ?',
                'attr'=>[
                    'placeholder' => 'Nommez votre adresse',
                ],
            ])

            ->add('firstname', TextType::class,[
                'label' => 'Votre prénom',
                'attr'=>[
                    'placeholder' => 'Entrez prénom',
                ],
            ])
            ->add('lastname', TextType::class,[
                'label' => 'Votre nom',
                'attr'=>[
                    'placeholder' => 'Entrez votre nom',
                ],
            ])
            ->add('company', TextType::class,[
                'label' => 'Votre entreprise (facultatif)',
                'required'=>false,
                'attr'=>[
                    'placeholder' => 'Renseignez le nom de votre entreprise',
                ],
            ])
            ->add('adress', TextType::class,[
                'label' => 'votre adresse de livraison',
                'attr'=>[
                    'placeholder' => 'Entrez votre adresse',
                ],
            ])
            ->add('postal', TextType::class,[
                'label' => 'Le code postal de votre adresse',
                'attr'=>[
                    'placeholder' => 'Entrez le code postal de votre adresse',
                ],
            ])
            ->add('city', TextType::class,[
                'label' => 'Votre ville',
                'attr'=>[
                    'placeholder' => 'Entrez votre ville',
                ],
            ])
            ->add('country', CountryType::class,[
                'label' => 'Votre pays',
                'attr'=>[
                    'placeholder' => 'Reseignez votre pays',
                ],
            ])
            ->add('phone', TelType::class,[
                'label' => 'Votre numéro de téléphone',
                'attr'=>[
                    'placeholder' => 'Tapez votre numéro de téléphone',
                ],
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Valider',
                'attr'=>[
                    'class' => 'btn btn-info btn-block mb-5'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adress::class,
        ]);
    }
}
