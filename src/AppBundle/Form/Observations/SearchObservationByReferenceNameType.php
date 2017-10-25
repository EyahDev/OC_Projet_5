<?php

namespace AppBundle\Form\Observations;

use AppBundle\Validator\SearchObservation\ContainsPeriodBegin;
use AppBundle\Validator\SearchObservation\ContainsPeriodEnd;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchObservationByReferenceNameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference', EntityType::class, array(
                'class' => 'AppBundle\Entity\Species',
                'choice_label' => 'referenceName',
            ))
            ->add('begin', DateTimeType::class, array(
                'label' => 'De',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'required' => false,
                'constraints' => array(
                    new ContainsPeriodBegin()
                )
            ))
            ->add('end', DateTimeType::class, array(
                'label' => 'A',
                'widget' => 'single_text',
                'required' => false,
                'format' => 'dd/MM/yyyy',
                'constraints' => array(
                    new ContainsPeriodEnd()
                )
            ))
            ->add('Rechercher', SubmitType::class);
    }
}