<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\TimerSettings;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;

/**
 * Class LoadTimerSettingsData.
 *
 * @author Lotte Verwerft
 */
class LoadTimerSettingsData extends AbstractFixture implements OrderedFixtureInterface {

    const COUNT = 7;

    /**
     * {@inheritdoc}
     */

    public function getOrder(){
        // Load this file second.
        return 13;
    }

    /**
     * {@inheritdoc}
     */

    public function load(ObjectManager $em): void
    {
        for ($itemCount = 0; $itemCount < self::COUNT; ++$itemCount) {
            $locale = 'nl_BE';
            $faker = Faker::create($locale);
            $timer = new TimerSettings();
            $em->persist($timer); // Manage Entity for persistence.
            $timer
                ->setPauseTime($this->getReference('Pause-' . $itemCount))
                ->setWorkTime($this->getReference('Work-' . $itemCount))
            ;
            $this->addReference("Timer-${itemCount}", $timer);

            $em->flush(); // Persist all managed Entities.
        }
    }
}