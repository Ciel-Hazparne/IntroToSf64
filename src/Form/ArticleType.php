<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                'required' => true,
                'label' => 'Nom de l\'article',
                'placeholder' => 'Entez le nom de l\'article : entre 5 et 50 caractères',
                ],
            ])
            ->add('price', TextType::class, [
                'attr' => [
                'required' => true,
                'label' => 'Prix de l\'article',
                'placeholder' => 'Entrez le prix de l\'article : il doit être supérieur à 0€',
                ],
            ])
            ->add('category',EntityType::class,[
                'class' => Category::class,
                'choice_label' => 'title',
                'label' => 'Catégorie',
            ])
            ->add('imageFile', FileType::class, [
                'required' => false,
                'label' => 'image de l\'article',
                'label_attr' => [
                    'data-browse' => 'Parcourir'
                ]
            ])
                    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
