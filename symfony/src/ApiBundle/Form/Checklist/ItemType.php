<?php

namespace ApiBundle\Form\Checklist;

use AppBundle\Entity\ChecklistItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('finished')
            ->add('name')
            ->add('description')
            ->add('estimatePomodoros')
            ->add('completedBy')
            ->add('isWorking')
            ->add('inProgress')
        ;

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ChecklistItem::class,
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
        return 'item';
    }
}