<?php

namespace AppBundle\Form\Observations;

use AppBundle\Validator\AddObservation\ContainsFileFormat;
use AppBundle\Validator\AddObservation\ContainsFileSize;
use AppBundle\Validator\AddObservation\ContainsBothName;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Valid;

class ModifObservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('species', EntityType::class, array(
                'label' => 'Espèces *',
                'class' => 'AppBundle\Entity\Species',
                'choice_label' => 'referenceName',
                'invalid_message' => 'Veuillez sélectionner une espèce valide.',
                'required' => false
            ))
            ->add('birdNumber', IntegerType::class, array(
                'label' => 'Nombre d\'oiseaux observés *',
                'attr' => array('min' => 1),
                'invalid_message' => 'Veuillez saisir un nombre d\'oiseaux observés valide',
                'constraints' => array(
                    new GreaterThanOrEqual(array(
                        'value' => 1,
                        'message' => 'Veuillez saisir un nombre d\'oiseaux observés valide'
                    )),
                    new NotBlank(array(
                        'message' => 'Veuillez saisir un nombre d\'oiseaux observés valide'
                    ))
                )
            ))
            ->add('eggsNumber', IntegerType::class, array(
                'label' => 'Nombres oeufs observés',
                'attr' => array('min' => 1),
                'invalid_message' => 'Veuillez saisir un nombre d\'oeufs observés valide',
                'required' => false,
                'constraints' => array(
                    new GreaterThanOrEqual(array(
                        'value' => 1,
                        'message' => 'Veuillez saisir un nombre d\'oeufs observés valide'
                    ))
            )))
            ->add('eggsDescription', TextareaType::class, array(
                'label' => 'Description des oeufs',
                'invalid_message' => 'Veuillez saisir une description des oeufs valide.',
                'required' => false,
                'constraints' => array(
                    new Length(array(
                        'max' => 255,
                        'maxMessage' => 'Votre description des oeufs ne peut dépasser {{ limit }} caratères'
                    )),
                )
            ))
            ->add('latitude', TextType::class, array(
                'label' => 'Latitude *',
                'invalid_message' => 'Veuillez saisir une latitude valide.'
            ))
            ->add('longitude', TextType::class, array(
                'label' => 'Longitude *',
                'invalid_message' => 'Veuillez saisir une longitude valide.'
            ))
            ->add('altitude', TextType::class,array(
                'label' => 'Altitude',
                'invalid_message' => 'Veuillez saisir une altitude valide.',
                'required' => false,
                'constraints' => array(
                    new Regex(array(
                        'pattern' => '/^[0-9]{1,4}m$/',
                        'message' => 'Veuillez saisir une altitude valide'
                    ))
                )
            ))
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
            ->add('validation_comment', TextareaType::class, array(
                'invalid_message' => 'Veuillez saisir un commentaire de validation valide.',
                'required' => false,
                'constraints' => array(
                    new Length(array(
                        'max' => 255,
                        'maxMessage' => 'Votre commentaire de validation ne peut dépasser {{ limit }} caratères'
                    )),
            )))
            ->add('Valider', SubmitType::class)
            ->add('Refuser', SubmitType::class);
    }
}
