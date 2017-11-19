<?php

namespace AppBundle\Form\Type\UserManagement;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChangeRoleType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('role', ChoiceType::class, array(
            'choices'  => array(
                'Administrateur' => 'ROLE_ADMIN',
                'Professionnel' => 'ROLE_PROFESSIONAL',
                'Particulier' => 'ROLE_USER',
            ),
            'invalid_message' => 'Veuillez saisir un rôle valide.',
            ))
            ->add('save', SubmitType::class,
            array(
                'label' => 'Modifier'
            )
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'error_bubbling' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'change_role';
    }
}
