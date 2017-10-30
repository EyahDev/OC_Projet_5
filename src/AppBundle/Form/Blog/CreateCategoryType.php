<?php

namespace AppBundle\Form\Blog;

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
            'invalid_message' => 'Veuillez saisir un nom catégorie valide.'
        ))
            ->add('photoPath', FileType::class, array(
                'label' => 'Sélectionnez une image répresantant la catégorie (si aucune image n\'est sélectionnée, une image par défaut sera ajoutée)',
                'invalid_message' => 'Veuillez sélectionner un fichier valide.',
                'required' => false
            ))
        ->add('save', SubmitType::class);
    }
}