<?php

namespace ApiBundle\Form\User;

use AppBundle\Entity\Groups;
use Nelmio\ApiDocBundle\Tests\Fixtures\Form\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Groups::class,
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
        return 'group';
    }
}