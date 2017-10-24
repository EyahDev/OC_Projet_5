<?php


namespace AppBundle\Form\Account;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UpdatePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', PasswordType::class,
                array(
                    'label' => 'Mot de passe actuel',
                    'required' => true
                )
            )
            ->add('newPassword', RepeatedType::class,
                array(
                    'type' => PasswordType::class,
                    'first_options' => array('label' => 'Nouveau mot de passe'),
                    'second_options' => array('label' => 'Confirmez le mot de passe'),
                    'invalid_message' => 'Les mots de passe doivent correspondre.',
                    'options' => array('attr' => array('class' => 'password-field')),
                    'required' => true,
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
        return 'update_password_form';
    }
}