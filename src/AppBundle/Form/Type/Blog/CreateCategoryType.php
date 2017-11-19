<?php

namespace AppBundle\Form\Type\Blog;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateCategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
            'label' => 'Définissez le nom de votre catégorie',
            'invalid_message' => 'Veuillez saisir un nom de catégorie valide.'
        ))
            ->add('photoPath', FileType::class, array(
                'label' => 'Sélectionner une image',
                'invalid_message' => 'Veuillez sélectionner un fichier valide.',
                'required' => false
            ))
        ->add('save', SubmitType::class);
    }
}
