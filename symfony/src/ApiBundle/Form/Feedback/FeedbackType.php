<?php

namespace ApiBundle\Form\Feedback;

use AppBundle\Entity\CustomerFeedback as Feedback;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeedbackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('feedback')
            ->add('request')
            ->add('submittedAt')
            ->add('answered')
        ;

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Feedback::class,
            'csrf_protection' => false,
            'Validation_groups' => [
                'Default'
            ]
        ));
    }

    /**
     * No prefix
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'feedback';
    }
}