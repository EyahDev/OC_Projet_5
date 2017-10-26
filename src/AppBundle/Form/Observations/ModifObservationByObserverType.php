<?php

namespace AppBundle\Form\Observations;

use AppBundle\Validator\AddObservation\ContainsFileFormat;
use AppBundle\Validator\AddObservation\ContainsFileSize;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class ModifObservationByObserverType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('observationDescription', TextareaType::class, array(
                'label' => 'Commentaire d\'observation',
                'invalid_message' => 'Veuillez saisir une description de l\'observation valide.',
                'required' => false,
                'constraints' => array(
                    new Length(array(
                        'max' => 255,
                        'maxMessage' => 'Votre description de l\'observation ne peut dépasser {{ limit }} caratères'
                    )),
            )))
            ->add('photoPath', FileType::class, array(
                'label' => 'Photo(s)',
                'invalid_message' => 'Veuillez sélectionner une fichier valide.',
                'data_class' => null,
                'constraints' => array(
                    new ContainsFileFormat(),
                    new ContainsFileSize(),
                ),
                'required' => false
            ))
            ->add('Modifier', SubmitType::class);
    }
}
