<?php

namespace AppBundle\Form\Blog;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', TextType::class, array(
                    'label' => 'Modifier le nom de votre catégorie',
                    'invalid_message' => 'Veuillez saisir une catégorie valide.'
                ))
                ->add('photoPath', FileType::class, array(
                    'label' => 'Modifiez l\'image représantant la catégorie',
                    'invalid_message' => 'Veuillez sélectionner une fichier valide.',
                    'data_class' => null,
                    'required' => false
                ))
                ->add('save', SubmitType::class);
    }
}