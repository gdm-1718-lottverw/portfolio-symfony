<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Issue;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;
use AppBundle\Services\HashCode;

/**
 * Class LoadIssueData.
 *
 * @author Lotte Verwerft
 */
class LoadIssueData extends AbstractFixture implements OrderedFixtureInterface {

    const COUNT = 50;

    /**
     * {@inheritdoc}
     */

    public function getOrder(){
        // Load this file second.
        return 9;
    }

    /**
     * {@inheritdoc}
     */

    public function load(ObjectManager $em): void
    {
        // Select all projects from the database. We will assign the issue to projects.
        $projects = $em->getRepository('AppBundle:Project')->findAll();
        $totalProjects = count($projects);
        $hash = new HashCode();
        for ($issueCount = 0; $issueCount < self::COUNT; ++$issueCount) {
            $locale = 'nl_BE';
            $faker = Faker::create($locale);
            $issue = new Issue();
            $em->persist($issue); // Manage Entity for persistence.
            $issue
                ->settitle($faker->words($nb = 3, $asText = true))
                ->setHash($hash->generateHashCode(10))
                ->setDescription($faker->paragraph($nbSentences = 3, $variableNbSentences = true))
                ->setSolved($faker->boolean($chanceOfGettingTrue = 50))
                ->setUrgent($faker->boolean($chanceOfGettingTrue = 10))
                ->setEstimatePomodoros($faker->numberBetween(1, 5))
                ->setProject($this->getReference('Project-'. rand(0, ($totalProjects-1))))
                ->setCreatedAt($faker->dateTimeBetween($startDate = '-3 months', $endDate = 'now'));
            if($issue->getSolved() == true){
                $issue->setSolvedBy($faker->words($nb = 3, $asText = true));
                $issue->setInProgress(false);
            } else {
                $issue->setInProgress($faker->boolean($chanceOfGettingTrue = 50));
                if($issue->getInProgress() == true ) {
                   $issue->setIsWorking($faker->words($nb = 2, $asText = true));
                }
            }
            $this->addReference("Issue-${issueCount}", $issue);
            $em->flush(); // Persist all managed Entities.
        }
    }
}
