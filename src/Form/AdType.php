<?php

namespace App\Form;

use App\Entity\Aad;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                'label'=>'Titre',
                'attr' =>[
                    'placeholder' => 'Le Titre ici'
                ]
            ])
            ->add('price', IntegerType::class,[
                'label'=>'Prix',
                'attr' =>[
                    'placeholder' => 'Uniqument un entier (1,2,3...)'
                ]
            ])
            ->add('content', TextareaType::class)
            ->add('imageFile', FileType::class, [
                'data_class' => null,
                'label'=>'Images',
                'required' => false,
                'allow_extra_fields' => true
            ])
            ->add('filename',TextType::class,[
                'label'=>'',
                'attr' =>[
                    'placeholder' => 'Quantité(entier)'
                ]
            ])
            ->add('submit', SubmitType::class,[
                'label'=>'Enregistrer',
                'attr' => [
                    'class'=>'btn btn-info btn-block'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Aad::class,
        ]);
    }
}
