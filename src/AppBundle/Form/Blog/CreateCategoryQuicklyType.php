<?php

namespace AppBundle\Form\Blog;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateCategoryQuicklyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Définissez le nom de votre catégorie',
                'invalid_message' => 'Veuillez saisir un nom de catégorie valide.',
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Créer'
            ));
    }
}