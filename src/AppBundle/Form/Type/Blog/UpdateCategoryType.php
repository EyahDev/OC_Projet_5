<?php

namespace AppBundle\Form\Type\Blog;

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
                    'label' => 'Modifiez le nom de votre catégorie',
                    'invalid_message' => 'Veuillez saisir une catégorie valide.'
                ))
                ->add('photoPath', FileType::class, array(
                    'label' => 'selectionner une image',
                    'invalid_message' => 'Veuillez sélectionner un fichier valide.',
                    'data_class' => null,
                    'required' => false
                ))
                ->add('save', SubmitType::class);
    }
}
