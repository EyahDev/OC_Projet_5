<?php

namespace AppBundle\Form\Type\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('newPassword', RepeatedType::class,
                array(
                    'type' => PasswordType::class,
                    'first_options' => array('label' => 'Nouveau mot de passe'),
                    'second_options' => array('label' => 'Confirmez le mot de passe'),
                    'invalid_message' => 'Les mots de passe doivent correspondre.',
                    'options' => array('attr' => array('class' => 'password-field')),
                    'required' => true,
                    'constraints' => array(
                        new NotBlank(array("message" => "Le mot de passe ne peut pas être vide.")),
                        new Length((array(
                            "min" => "6",
                            "minMessage" => "Votre mot de passe doit contenir au moins 6 caractères"))),
                        new Regex(array(
                            "pattern" => "/^(?=.*[a-zA-Z])(?=.*[0-9])/",
                            "match" => "true",
                            "message" => "Votre mot de passe doit contenir au moins une lettre et un chiffre."
                        ))
                    )
                )
            )
            ->add('save', SubmitType::class,
                array(
                    'label' => 'Modifier'
                )
            )
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
        return 'reset_password';
    }
}
