<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Pause;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;

/**
 * Class LoadPauseTimeData.
 *
 * @author Lotte Verwerft
 */
class LoadPauseTimeData extends AbstractFixture implements OrderedFixtureInterface {

    const COUNT = 7;

    /**
     * {@inheritdoc}
     */

    public function getOrder(){
        // Load this file second.
        return 11;
    }

    /**
     * {@inheritdoc}
     */

    public function load(ObjectManager $em): void
    {
        for ($itemCount = 0; $itemCount < self::COUNT; ++$itemCount) {
            $locale = 'nl_BE';
            $faker = Faker::create($locale);
            $pause = new Pause();
            $em->persist($pause); // Manage Entity for persistence.
            $pause
                ->setSeconds($faker->numberBetween($min = 0, $max = 60))
                ->setMinutes($faker->numberBetween($min = 0, $max = 60))
                ->setHours($faker->numberBetween($min = 0, $max = 60));
            $this->addReference("Pause-${itemCount}", $pause);

            $em->flush(); // Persist all managed Entities.
        }
    }
}