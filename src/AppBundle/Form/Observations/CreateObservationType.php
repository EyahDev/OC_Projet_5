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
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Valid;

class CreateObservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('species', EntityType::class, array(
                'label' => 'Espèces *',
                'class' => 'AppBundle\Entity\Species',
                'choice_label' => 'referenceName',
                'required' => false
            ))
            ->add('vernacularName', EntityType::class, array(
                'label' => 'Ou Nom commun *',
                'class' => 'AppBundle\Entity\Species',
                'choice_label' => 'vernacularName',
                'query_builder' => function(EntityRepository $repository){
                    return $repository->createQueryBuilder('s')
                        ->where('s.vernacularName != :empty')
                        ->setParameter('empty', '')
                        ->orderBy('s.vernacularName', 'ASC');
                },
                'required' => false,
                'constraints' => array(
                    new ContainsBothName(),
                    new ContainsFileSize()
                ),
            ))
            ->add('birdNumber', IntegerType::class, array(
                'label' => 'Nombre d\'oiseaux observés *',
                'attr' => array('min' => 1),
                'constraints' => array(
                    new GreaterThanOrEqual(array(
                        'value' => 1,
                        'message' => 'Veuillez sélectionner un nombre d\'oiseaux observés valide'
                    )),
                    new NotBlank(array(
                        'message' => 'Veuillez sélectionner un nombre d\'oiseaux observés valide'
                    ))
                )
            ))
            ->add('eggsNumber', IntegerType::class, array(
                'label' => 'Nombres oeufs observés',
                'attr' => array('min' => 1),
                'required' => false,
                'constraints' => array(
                    new GreaterThanOrEqual(array(
                        'value' => 1,
                        'message' => 'Veuillez sélectionner un nombre d\'oeufs observés valide'
                    ))
            )))
            ->add('eggsDescription', TextareaType::class, array(
                'label' => 'Description des oeufs',
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
            ))
            ->add('longitude', TextType::class, array(
                'label' => 'Longitude *'
            ))
            ->add('altitude', TextType::class,array(
                'label' => 'Altitude',
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
                'required' => false,
                'constraints' => array(
                    new Length(array(
                        'max' => 255,
                        'maxMessage' => 'Votre description des oeufs ne peut dépasser {{ limit }} caratères'
                    )),
            )))
            ->add('photoPath', FileType::class, array(
                'label' => 'Photo(s)',
                'multiple' => true,
                'constraints' => array(
                    new ContainsFileFormat(),
                    new ContainsFileSize()
                ),
                'required' => false
            ))
            ->add('Envoyer', SubmitType::class);
    }
}
