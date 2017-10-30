<?php

namespace AppBundle\Form\Account;

use AppBundle\Validator\AddObservation\ContainsFileFormat;
use AppBundle\Validator\AddObservation\ContainsFileSize;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateAvatarType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('avatarPath', FileType::class, array(
                'label' => 'Modifier votre avatar',
                'invalid_message' => 'Veuillez sélectionner une fichier valide.',
                'data_class' => null,
                'constraints' => array(
                    new ContainsFileFormat(),
                    new ContainsFileSize(),
                ),
                'required' => false
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
