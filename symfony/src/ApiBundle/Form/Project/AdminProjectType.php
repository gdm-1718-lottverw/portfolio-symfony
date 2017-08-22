<?php

namespace ApiBundle\Form\Checklist;

use ApiBundle\Form\Feedback\FeedbackType;
use ApiBundle\Form\User\GroupType;
use ApiBundle\Form\Issue\IssueType;
use ApiBundle\Form\Task\TaskType;
use AppBundle\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ChecklistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('task', CollectionType::class, array(
                    'entry_type' => TaskType::class
                )
            )
            ->add('issue', CollectionType::class, array(
                    'entry_type' => IssueType::class
                )
            )
            ->add('checklist', CollectionType::class, array(
                    'entry_type' => ChecklistType::class
                )
            )
            ->add('feedback', CollectionType::class, array(
                    'entry_type' => FeedbackType::class
                )
            )
            ->add('group', CollectionType::class, array(
                    'entry_type' => GroupType::class
                )
            )
            ->add('title')
            ->add('customer')
            ->add('deadline')
            ->add('description')
            ->add('finished')
            ->add('inProgress')
            ->add('finishedAt');

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
        return 'admin_project';
    }
}