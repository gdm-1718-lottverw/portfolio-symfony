<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Location;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use DateTime;
/**
 * Class LoadLocationData.
 *
 * @author Lotte Verwerft
 */
class LoadLocationData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface {

    const COUNT = 10;

    /**
     * {@inheritdoc}
     */
    public function getOrder(){
        // Load this file second.
        return 8;
    }

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */

    public function load(ObjectManager $em): void
    {
        $locale = 'nl_BE';
        $faker = Faker::create($locale);
        for ($count = 0; $count < self::COUNT; ++$count) {

            $location = new Location();
            $em->persist($location);
            $location
                ->setAlias($faker->words($nb = 3, $asText = true))
                ->setAdress("Eekhoutstraat 14, Grobbendonk 2280")
                ->setCompany($faker->words($nb = 3, $asText = true))
                ->setActive(false)
                ->setCreatedAt(new DateTime());
            $this->addReference("Location-${count}", $location);
            $em->flush(); // Persist all managed Entities.

        }
    }
}


