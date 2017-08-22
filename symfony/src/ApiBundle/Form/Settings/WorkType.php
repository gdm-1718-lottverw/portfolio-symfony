<?php

namespace ApiBundle\Form\Settings;

use AppBundle\Entity\Work;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hours')
            ->add('minutes')
            ->add('seconds')
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Work::class,
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
        return 'work_time';
    }
}