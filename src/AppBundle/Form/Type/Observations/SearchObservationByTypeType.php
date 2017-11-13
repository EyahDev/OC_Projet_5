<?php

namespace AppBundle\Form\Type\Observations;

use AppBundle\Validator\SearchObservation\ContainsPeriodBegin;
use AppBundle\Validator\SearchObservation\ContainsPeriodEnd;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchObservationByTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', EntityType::class, array(
                'label' => 'Ordre',
                'class' => 'AppBundle\Entity\SpeciesType',
                'choice_label' => 'name',
                'invalid_message' => 'Veuillez saisir une espèce valide.',
            ))
            ->add('begin', DateTimeType::class, array(
                'label' => 'De',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'required' => false,
                'invalid_message' => 'Veuillez saisir une date de début valide.',
                'constraints' => array(
                    new ContainsPeriodBegin()
                )
            ))
            ->add('end', DateTimeType::class, array(
                'label' => 'A',
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
