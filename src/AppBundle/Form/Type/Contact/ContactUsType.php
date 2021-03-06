<?php

namespace AppBundle\Form\Type\Contact;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactUsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sujet', TextType::class, array(
                    'constraints' => array(
                    new NotBlank(array("message" => "Veuillez saisir un sujet valide.")),
                    new Length(array(
                        'min' => '2',
                        'minMessage' => 'Le sujet doit comporter au minimun {{ limit }} caractères.'
                    ))
                )
            ))
            ->add('message', TextareaType::class, array(
                    'constraints' => array(
                    new NotBlank(array("message" => "Veuillez saisir un message valide.")),
                    new Length(array(
                        'min' => '2',
                        'minMessage' => 'Votre message doit comporter au minimun {{ limit }} caractères.'
                    ))
                )
            ))
            ->add('save', SubmitType::class)
        ;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'error_bubbling' => true
        ));
    }

    public function getName()
    {
        return 'contactUs_form';
    }
}
