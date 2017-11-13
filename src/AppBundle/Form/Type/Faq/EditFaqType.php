<?php

namespace AppBundle\Form\Type\Faq;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditFaqType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('question', TextType::class, array(
            'label' => 'Question',
            'invalid_message' => 'Veuillez saisir une question valide.'
        ))
            ->add('answer', TextareaType::class, array(
                'label' => "Réponse",
                'invalid_message' => 'Veuillez saisir une réponse valide.'
            ))
            ->add('save', SubmitType::class,
                array(
                    'label' => 'Modifier'
                ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Faq'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'edit_faq';
    }
}
