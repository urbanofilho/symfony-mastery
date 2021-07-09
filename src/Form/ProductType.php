<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome',null, [
                'label' => 'Nome do Produto'
            ])
            ->add('description',null, [
                'label' => 'Descrição do Produto'
            ])
            ->add('body', TextType::class,[
                'label' => 'Conteúdo do Produto'
            ])
            ->add('price',null, [
                'label' => 'Preço do Produto'
            ])

            ->add('photos', FileType::class,[
                'mapped' => false,
                'multiple' => true
            ])

            ->add('category',null, [
                'label' => 'Categorias do Produto'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
