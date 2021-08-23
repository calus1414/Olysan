<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                "required" => false,
                "label" => "Nom du produit"
            ])
            ->add('description', TextareaType::class, [
                "required" => false,
                "label" => "Description du produit"
            ])
            ->add('price',  IntegerType::class, [
                "required" => false,
                "label" => "Prix du produit"
            ])
            ->add("category", EntityType::class, [
                "class" => Category::class,
                "choice_label" => "name",
                "required" => false,
                "label" => "Categorie du produit"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
