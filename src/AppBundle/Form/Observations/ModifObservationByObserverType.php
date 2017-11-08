<?php

namespace AppBundle\Form\Observations;

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
                'label' => 'Commentaire:',
                'invalid_message' => 'Veuillez saisir une description de l\'observation valide.',
                'required' => false,
                'constraints' => array(
                    new Length(array(
                        'max' => 255,
                        'maxMessage' => 'Votre description de l\'observation ne peut dépasser {{ limit }} caratères.'
                    )),
            )))
            ->add('photoPath', FileType::class, array(
                'label' => 'Changer photo',
                'invalid_message' => 'Veuillez sélectionner une fichier valide.',
                'data_class' => null,
                'required' => false
            ))
            ->add('save', SubmitType::class);
    }
}
