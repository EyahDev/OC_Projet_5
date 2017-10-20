<?php
/**
 * Created by PhpStorm.
 * User: Adrien
 * Date: 20/10/2017
 * Time: 11:09
 */

namespace AppBundle\Form\Observations;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchObservationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('NomScientifique', EntityType::class, array(
                'class' => 'AppBundle\Entity\Species',
                'choice_label' => 'referenceName',
                'required' => false
            ))
            ->add('NomCommun', EntityType::class, array(
                'class' => 'AppBundle\Entity\Species',
                'choice_label' => 'vernacularName',
                'required' => false
            ))
            ->add('Type', EntityType::class, array(
                'class' => 'AppBundle\Entity\Species',
                'choice_label' => 'type',
                'required' => false
            ))
            ->add('Famille', EntityType::class, array(
                'class' => 'AppBundle\Entity\Species',
                'choice_label' => 'family',
                'required' => false
            ))
            ->add('Rechercher', SubmitType::class);
    }
}