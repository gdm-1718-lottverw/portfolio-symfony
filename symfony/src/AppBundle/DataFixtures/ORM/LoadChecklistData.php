<?php
/**
 * Created by PhpStorm.
 * User: Lotte
 * Date: 20/06/17
 * Time: 15:27
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Checklist;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;

/**
 * Class LoadChecklistData.
 *
 * @author Lotte Verwerft
 */
class LoadChecklistData extends AbstractFixture implements OrderedFixtureInterface {

    const COUNT = 6;

    /**
     * {@inheritdoc}
     */

    public function getOrder(){
        // Load this file second.
        return 1;
    }

    /**
     * {@inheritdoc}
     */

    public function load(ObjectManager $em): void
    {
        for ($checklistCount = 0; $checklistCount < self::COUNT; ++$checklistCount) {
            $locale = 'nl_BE';
            $faker = Faker::create($locale);
            $checklist = new Checklist();
            $em->persist($checklist); // Manage Entity for persistence.
            $checklist
                ->setCreatedAt($faker->dateTimeBetween($startDate = '-3 months', $endDate = 'now'));
            $this->addReference("Checklist-${checklistCount}", $checklist);
            $em->flush(); // Persist all managed Entities.
        }

    }
}




