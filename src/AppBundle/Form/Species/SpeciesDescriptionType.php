<?php

namespace AppBundle\Form\Species;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class SpeciesDescriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextareaType::class, array(
                'attr' => array(
                    'class' => 'tinyMCE'
                ),
                'invalid_message' => 'Veuillez saisir une description valide.'
                ))
            ->add('modifier', SubmitType::class);
    }
}
