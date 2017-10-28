<?php

namespace AppBundle\Form\Blog;

use AppBundle\Validator\AddObservation\ContainsFileFormat;
use AppBundle\Validator\AddObservation\ContainsFileSize;
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
                    'label' => 'Modifier le nom de votre catégorie'
                ))
                ->add('photoPath', FileType::class, array(
                    'label' => 'Modifiez l\'image représantant la catégorie',
                    'invalid_message' => 'Veuillez sélectionner une fichier valide.',
                    'data_class' => null,
                    'constraints' => array(
                        new ContainsFileFormat(),
                        new ContainsFileSize(),
                    ),
                    'required' => false
                ))
                ->add('save', SubmitType::class);
    }
}