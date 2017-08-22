<?php

namespace ApiBundle\Form\Checklist;

use AppBundle\Entity\Checklist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ChecklistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('items', CollectionType::class, array(
            'entry_type' => ItemType::class,
            'by_reference'    => false,
        ))
        ;

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Checklist::class,
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
        return 'checklist';
    }
}