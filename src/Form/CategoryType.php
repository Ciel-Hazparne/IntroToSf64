<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'required' => true,
                    'label' => 'Nom de la catégorie',
                    'placeholder' => 'Entrez le nom de la catégorie',
                ],
            ])
            ->add('description', TextType::class, [
                'attr' => [
                    'required' => true,
                    'label' => 'Description de la catégorie',
                    'placeholder' => 'Expliquez en quelques lignes le contenu de la catégorie',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
