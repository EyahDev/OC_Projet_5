<?php

namespace AppBundle\Form\Observations;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateObservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('species', EntityType::class, array(
                'class' => 'AppBundle\Entity\Species',
                'choice_label' => 'referenceName'
            ))
            ->add('birdNumber', IntegerType::class)
            ->add('eggsNumber', IntegerType::class)
            ->add('eggsDescription', TextareaType::class)
            ->add('longitude', TextType::class)
            ->add('latitude', TextType::class)
            ->add('altitude', TextType::class)
            ->add('observationDescription', TextareaType::class)
            ->add('photoPath', FileType::class)
            ->add('save', SubmitType::class);
    }
}
