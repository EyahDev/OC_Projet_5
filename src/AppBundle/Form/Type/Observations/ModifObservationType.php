<?php

namespace AppBundle\Form\Type\Observations;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class ModifObservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('species', EntityType::class, array(
                'label' => 'Espèces',
                'placeholder' => '-- Sélectionnez une espèce --',
                'class' => 'AppBundle\Entity\Species',
                'choice_label' => 'referenceName',
                'invalid_message' => 'Veuillez sélectionner une espèce valide.',
            ))
            ->add('validation_comment', TextareaType::class, array(
                'label' => 'Commentaire de validation',
                'invalid_message' => 'Veuillez saisir un commentaire de validation valide.',
                'required' => false,
                'constraints' => array(
                    new Length(array(
                        'max' => 255,
                        'maxMessage' => 'Votre commentaire de validation ne peut dépasser {{ limit }} caratères.'
                    )),
            )))
            ->add('Valider', SubmitType::class)
            ->add('Refuser', SubmitType::class);
    }
}
