<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Pomodoro;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;

/**
 * Class LoadPomodoroData.
 *
 * @author Lotte Verwerft
 */
class LoadPomodoroData extends AbstractFixture implements OrderedFixtureInterface {

    const COUNT = 50;

    /**
     * {@inheritdoc}
     */

    public function getOrder(){
        // Load this file second.
        return 14;
    }

    /**
     * {@inheritdoc}
     */

    public function load(ObjectManager $em): void
    {
        for ($itemCount = 0; $itemCount < (self::COUNT / 3) ; ++$itemCount) {
        
            $locale = 'nl_BE';
            $faker = Faker::create($locale);
            $pomodoro = new Pomodoro();
            $em->persist($pomodoro); // Manage Entity for persistence.
            $pomodoro
                ->setTask($this->getReference('Task-'. $itemCount))
                ->setFinished($faker->boolean($chanceOfGettingTrue = 90))
                ->setTime($faker->numberBetween(0, 60));
            $this->addReference("Pomodoro-${itemCount}", $pomodoro);
            
            if($pomodoro->getFinished() == true) {
                $pomodoro->setInProgress(false);
            } else {
                $pomodoro->setInProgress($faker->boolean($chanceOfGettingTrue = 10));
            }
            $em->flush(); // Persist all managed Entities.
        }
        for ($itemCount = round(self::COUNT / 3); $itemCount < (self::COUNT - (self::COUNT / 3)) ; ++$itemCount) {
            $locale = 'nl_BE';
            $faker = Faker::create($locale);
              $pomodoro = new Pomodoro();
            $em->persist($pomodoro); // Manage Entity for persistence.
            $pomodoro
                ->setItem($this->getReference('Item-'. $itemCount))
                ->setFinished($faker->boolean($chanceOfGettingTrue = 90))
                    ->setTime($faker->numberBetween(0, 60));
            $this->addReference("Pomodoro-${itemCount}", $pomodoro);
            if($pomodoro->getFinished() == true) {
                $pomodoro->setInProgress(false);
            } else {
                $pomodoro->setInProgress($faker->boolean($chanceOfGettingTrue = 10));
            }
            $em->flush(); // Persist all managed Entities.
        }
         for ($itemCount = round(self::COUNT - (self::COUNT / 3) + 1 ); $itemCount < self::COUNT; ++$itemCount) {
            $locale = 'nl_BE';
            $faker = Faker::create($locale);
              $pomodoro = new Pomodoro();
            $em->persist($pomodoro); // Manage Entity for persistence.
            $pomodoro
                ->setIssue($this->getReference('Issue-'. $itemCount))
                ->setFinished($faker->boolean($chanceOfGettingTrue = 90))
                ->setTime($faker->numberBetween(0, 60));
            $this->addReference("Pomodoro-${itemCount}", $pomodoro);
            if($pomodoro->getFinished() == true) {
                $pomodoro->setInProgress(false);
            } else {
                $pomodoro->setInProgress($faker->boolean($chanceOfGettingTrue = 10));
            }
            $em->flush(); // Persist all managed Entities.
        }
     
    }
}