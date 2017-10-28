<?php

namespace AppBundle\Form\Blog;

use AppBundle\Validator\AddObservation\ContainsFileFormat;
use AppBundle\Validator\AddObservation\ContainsFileSize;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreatePostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'label' => "Titre de l'article"
            ))
            ->add('category', EntityType::class, array(
                'class' => 'AppBundle:Category',
                'label' => "Catégorie",
                'choice_label' => 'name',
                'placeholder' => '-- Sélectionnez une catégorie -- '
            ))
            ->add('content', TextareaType::class, array(
                'label' => "Contenu de l'article"
            ))
            ->add('imagePath', FileType::class, array(
                'label' => 'Sélectionnez une image représant votre article',
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
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Post'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_post';
    }
}
