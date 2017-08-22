<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Work;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;

/**
 * Class LoadWorkTimeData.
 *
 * @author Lotte Verwerft
 */
class LoadWorkTimeData extends AbstractFixture implements OrderedFixtureInterface {

    const COUNT = 7;

    /**
     * {@inheritdoc}
     */

    public function getOrder(){
        // Load this file second.
        return 12;
    }

    /**
     * {@inheritdoc}
     */

    public function load(ObjectManager $em): void
    {
        for ($itemCount = 0; $itemCount < self::COUNT; ++$itemCount) {
            $locale = 'nl_BE';
            $faker = Faker::create($locale);
            $work = new Work();
            $em->persist($work); // Manage Entity for persistence.
            $work
                ->setSeconds($faker->numberBetween($min = 0, $max = 60))
                ->setMinutes($faker->numberBetween($min = 0, $max = 60))
                ->setHours($faker->numberBetween($min = 0, $max = 60));

            $this->addReference("Work-${itemCount}", $work);

            $em->flush(); // Persist all managed Entities.
        }
    }
}