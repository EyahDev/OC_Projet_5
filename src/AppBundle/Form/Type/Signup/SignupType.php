<?php

namespace AppBundle\Form\Type\Signup;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class SignupType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Votre nom',
                'invalid_message' => 'Veuillez saisir un nom valide.',
            ))
            ->add('firstname', TextType::class, array(
                'label' => 'Votre prénom',
                'invalid_message' => 'Veuillez saisir un prénom valide.',
            ))
            ->add('username', TextType::class, array(
                'label' => 'Votre nom d\'utilisateur',
                'invalid_message' => 'Veuillez saisir un nom d\'utilisateur valide.',
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Votre adresse mail',
                'invalid_message' => 'Veuillez saisir une adresse mail valide.',
            ))
            ->add('password', PasswordType::class, array(
                'label' => 'Votre mot de passe',
                'invalid_message' => 'Veuillez saisir un mot de passe valide.',
            ))
            ->add('proPermission', CheckboxType::class, array(
                'label'    => 'Etes vous pro ?',
                'invalid_message' => 'Veuillez saisir un choix pro valide.',
                'required' => false,
            ))
            ->add('newsletter', CheckboxType::class, array(
                'label'    => 'Abonnement à la newsletter',
                'invalid_message' => 'Veuillez saisir un choix de newsletter valide.',
                'required' => false,
            ))
            ->add('save', SubmitType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }
}
