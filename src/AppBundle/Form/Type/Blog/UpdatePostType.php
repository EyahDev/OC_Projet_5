<?php

namespace AppBundle\Form\Type\Blog;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdatePostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'label' => "Titre de l'article",
                'invalid_message' => 'Veuillez saisir un titre d\'article valide.'
            ))
            ->add('category', EntityType::class, array(
                'class' => 'AppBundle:Category',
                'label' => "Sélectionnez une catégorie",
                'choice_label' => 'name',
                'invalid_message' => 'Veuillez saisir une catégorie valide.',
            ))
            ->add('content', TextareaType::class, array(
                'label' => "Contenu de l'article",
                'invalid_message' => 'Veuillez saisir un contenu valide.'
            ))
            ->add('imagePath', FileType::class, array(
                'label' => 'Sélectionnez une image',
                'invalid_message' => 'Veuillez sélectionner un fichier valide.',
                'data_class' => null,
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
        return 'update_post';
    }
}
