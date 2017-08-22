<?php

namespace ApiBundle\Form\Project;

use AppBundle\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('customer')
            ->add('deadline', DateType::class, array(
                'widget' => 'single_text',
               
            ))
            ->add('description')
            ->add('finishedAt', DateType::class, array(
                'widget' => 'single_text',
               
            ))
            ->add('finished')
            ->add('inProgress')

        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Project::class,
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
        return 'project';
    }
}