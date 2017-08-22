<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;
use AppBundle\Services\HashCode;
use FOS\UserBundle\Model\User;

/**
 * Class LoadGroupData.
 *
 * @author Lotte Verwerft
 */
class LoadGroupData extends AbstractFixture implements OrderedFixtureInterface {

    const COUNT = 4;

    /**
     * {@inheritdoc}
     */

    public function getOrder(){
        // Load this file second.
        return 3;
    }

    /**
     * {@inheritdoc}
     */

    public function load(ObjectManager $em): void
    {
        // Select all projects from the database. We will assign the group to projects.
        $projects = $em->getRepository('AppBundle:Project')->findAll();
        $totalProjects = count($projects);
        $hash = new HashCode();
        for ($groupCount = 0; $groupCount < self::COUNT; ++$groupCount) {
            $locale = 'nl_BE';
            $faker = Faker::create($locale);
            $group = new Groups();
            $em->persist($group); // Manage Entity for persistence.
            $group
                ->setName($faker->words($nb = 3, $asText = true))
                ->setRoles(array('ROLE_ADMIN'))
            ;
            $this->addReference("Group-${groupCount}", $group);
            $em->flush(); // Persist all managed Entities.
        }
    }
}
