<?php

namespace ApiBundle\Form\Pomodoro;

use AppBundle\Entity\Pomodoro;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PomodoroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('inProgress')
            ->add('time')
            ->add('finished')
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Pomodoro::class,
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
        return 'pomodoro';
    }
}