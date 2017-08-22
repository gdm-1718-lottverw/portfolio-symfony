<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Profile;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadProfileData.
 *
 * @author Lotte Verwerft
 */
class LoadProfileData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface {

    const COUNT = 4;

    /**
     * {@inheritdoc}
     */
    public function getOrder(){
        // Load this file second.
        return 9;
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
        $user = count($em->getRepository('AppBundle:User')->findAll());
        for ($count = 1; $count < self::COUNT; ++$count){
           
            $profile = new Profile();
            $em->persist($profile); // Manage Entity for persistence.
            $profile
                ->setisActive($faker->boolean($chanceOfGettingTrue = 50))
                ->setUser($this->getReference('User-'. $count))
                ->addLocation($this->getReference('Location-'. $count))
            ;
            $this->addReference("Profile-${count}", $profile);
            $em->flush(); // Persist all managed Entities.
        }

    }
}
