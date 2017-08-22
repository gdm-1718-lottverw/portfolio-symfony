<?php

namespace ApiBundle\Form\Issue;

use AppBundle\Entity\Issue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('solved')
            ->add('title')
            ->add('description')
            ->add('estimatePomodoros')
            ->add('solvedBy')
            ->add('isWorking')
            ->add('inProgress')
            ->add('urgent')
        ;

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Issue::class,
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
        return 'issue';
    }
}