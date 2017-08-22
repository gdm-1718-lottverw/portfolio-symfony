<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\CustomerFeedback;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;

/**
 * Class LoadCustomerFeedbackData.
 *
 * @author Lotte Verwerft
 */
class LoadCustomerFeedbackData extends AbstractFixture implements OrderedFixtureInterface {

    const COUNT = 20;

    /**
     * {@inheritdoc}
     */

    public function getOrder(){
        // Load this file first.
        return 10;
    }

    /**
     * {@inheritdoc}
     */

    public function load(ObjectManager $em): void
    {
        $projects = $em->getRepository('AppBundle:Project')->findAll();
        $totalProjects = count($projects);

        for ($customerFeedbackCount = 0; $customerFeedbackCount < self::COUNT; ++$customerFeedbackCount) {
            $locale = 'nl_BE';
            $faker = Faker::create($locale);
            $customerFeedback = new CustomerFeedback();
            $em->persist($customerFeedback); // Manage Entity for persistence.
            $customerFeedback
                ->setFeedback($faker->words($nb = 10, $asText = true))
                ->setRequest($faker->words($nb = 15, $asText = true))
                ->setAnswered($faker->boolean($chanceOfGettingTrue = 50))
                ->setSubmittedAt($faker->dateTimeBetween($startDate = "- 1 year", $endDate = "now"))
                ->setProject($this->getReference('Project-'. rand(1, ($totalProjects - 1))))

            ;

            $this->addReference("customerFeedback${customerFeedbackCount}", $customerFeedback);
            $em->flush(); // Persist all managed Entities.
        }
    }
}