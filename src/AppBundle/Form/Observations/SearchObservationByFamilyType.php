<?php

namespace AppBundle\Form\Observations;

use AppBundle\Validator\SearchObservation\ContainsPeriodBegin;
use AppBundle\Validator\SearchObservation\ContainsPeriodEnd;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchObservationByFamilyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('family', EntityType::class, array(
                'placeholder' => '-- Sélectionnez une famille',
                'label' => 'Famille',
                'class' => 'AppBundle\Entity\SpeciesFamily',
                'choice_label' => 'name',
                'invalid_message' => 'Veuillez saisir une espèce valide.',
            ))
            ->add('De', DateTimeType::class, array(
                'label' => 'De',
                'placeholder' => 'ex : 01/01/1970',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'required' => false,
                'invalid_message' => 'Veuillez saisir une date de début valide.',
                'constraints' => array(
                    new ContainsPeriodBegin()
                )
            ))
            ->add('A', DateTimeType::class, array(
                'label' => 'A',
                'placeholder' => 'ex : 01/01/2013',
                'widget' => 'single_text',
                'required' => false,
                'format' => 'dd/MM/yyyy',
                'invalid_message' => 'Veuillez saisir une date de fin valide.',
                'constraints' => array(
                    new ContainsPeriodEnd()
                )
            ))
            ->add('Rechercher', SubmitType::class);
    }
}