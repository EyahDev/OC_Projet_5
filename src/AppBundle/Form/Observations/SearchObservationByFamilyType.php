<?php
/**
 * Created by PhpStorm.
 * User: Adrien
 * Date: 20/10/2017
 * Time: 11:09
 */

namespace AppBundle\Form\Observations;

use AppBundle\Validator\SearchObservation\ContainsFileFormat;
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
                'label' => 'Famille',
                'class' => 'AppBundle\Entity\SpeciesFamily',
                'choice_label' => 'name'
            ))
            ->add('De', DateTimeType::class, array(
                'label' => 'De',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'required' => false,
                'constraints' => array(
                    new ContainsPeriodBegin()
                )
            ))
            ->add('A', DateTimeType::class, array(
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