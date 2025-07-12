<?php

namespace App\Form;

use App\Entity\Category;
use App\Model\CategoryPriceSearch;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryPriceSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'label' => 'Choisissez une catégorie',
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false
            ])
            ->add('minPrice', TextType::class, [
                'label' => 'Entrez le prix minimal',
                'required' => false
            ])
            ->add('maxPrice', TextType::class, [
                'label' => 'Entrez le prix maximal',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CategoryPriceSearch::class,
            'method' => 'GET',  // facultatif mais conseillé ici -> recherche
            'csrf_protection' => false,
        ]);
    }
}
