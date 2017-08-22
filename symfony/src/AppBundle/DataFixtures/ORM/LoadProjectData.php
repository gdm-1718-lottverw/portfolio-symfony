<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Project;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;
use AppBundle\Services\HashCode;

/**
 * Class LoadProjectData.
 *
 * @author Lotte Verwerft
 */
class LoadProjectData extends AbstractFixture implements OrderedFixtureInterface {

    const COUNT = 6;

    /**
     * {@inheritdoc}
     */

    public function getOrder(){
        // Load this file first.
        return 4;
    }

    /**
     * {@inheritdoc}
     */

    public function load(ObjectManager $em): void
    {
        for ($projectCount = 0; $projectCount < self::COUNT; ++$projectCount) {
            $locale = 'nl_BE';
            $hash = new HashCode();
            $faker = Faker::create($locale);
            $project = new Project();
            $em->persist($project); // Manage Entity for persistence.
            $project
                ->setTitle($faker->words($nb = 3, $asText = true))
                ->setHash($hash->generateHashCode(10))
                ->setCustomer($faker->words($nb = 4, $asText = true))
                ->setDescription($faker->paragraph($nbSentences = 3, $variableNbSentences = true))
                ->setFinished($faker->boolean($chanceOfGettingTrue = 3))
                ->setDeadline($faker->dateTime($max = '+ 2 years'))
                ->setChecklist($this->getReference('Checklist-'.$projectCount))
                ->setGroup($this->getReference('Group-'. rand(0,3)))
                ->setCreatedAt($faker->dateTimeBetween($startDate = '-3 months', $endDate = 'now'));
            // If a project is finished the in progress is false.
            if ($project->getFinished() == true){
                $project->setInProgress(false);
                $project->setFinishedAt($faker->dateTime($max = 'now'));
            } else {
                $project->setInProgress(true);
            }
            $this->addReference("Project-${projectCount}", $project);
            $em->flush(); // Persist all managed Entities.
        }
    }
    
}