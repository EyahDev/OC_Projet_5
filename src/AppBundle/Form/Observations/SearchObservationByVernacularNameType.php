<?php

namespace AppBundle\Form\Observations;

use AppBundle\Validator\ContainsPeriodBegin;
use AppBundle\Validator\ContainsPeriodEnd;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchObservationByVernacularNameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vernacular', EntityType::class, array(
                'label' => 'Nom commun',
                'class' => 'AppBundle\Entity\Species',
                'choice_label' => 'vernacularName',
                'query_builder' => function(EntityRepository $repository){
                    return $repository->createQueryBuilder('s')
                        ->where('s.vernacularName != :empty')
                        ->setParameter('empty', '')
                        ->orderBy('s.vernacularName', 'ASC');
                }
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