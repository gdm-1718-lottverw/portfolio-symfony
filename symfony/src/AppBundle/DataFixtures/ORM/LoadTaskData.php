<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Task;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;
use AppBundle\Services\HashCode;

/**
 * Class LoadTaskData.
 *
 * @author Lotte Verwerft
 */
class LoadTaskData extends AbstractFixture implements OrderedFixtureInterface {

    const COUNT = 20;

    /**
     * {@inheritdoc}
     */

    public function getOrder(){
        // Load this file second.
        return 8;
    }

    /**
     * {@inheritdoc}
     */

    public function load(ObjectManager $em): void
    {
        // Select all projects from the database. We will assign the task to projects.
        $projects = $em->getRepository('AppBundle:Project')->findAll();
        $totalProjects = count($projects);
        $hash = new HashCode();
        for ($taskCount = 0; $taskCount < self::COUNT; ++$taskCount) {
            $locale = 'nl_BE';
            $faker = Faker::create($locale);
            $task = new Task();
            $em->persist($task); // Manage Entity for persistence.
            $task
                ->setName($faker->words($nb = 3, $asText = true))
                ->setHash($hash->generateHashCode(10))
                ->setEstimatePomodoros($faker->numberBetween(1, 5))
                ->setDescription($faker->paragraph($nbSentences = 3, $variableNbSentences = true))
                ->setFinished($faker->boolean($chanceOfGettingTrue = 30))
                ->setInProgress($faker->boolean($chanceOfGettingTrue = 10))
                ->setProject($this->getReference('Project-'. rand(0, ($totalProjects -1))))
                ->setUser($this->getReference('User-'. rand(1, 3)))
                ->setCreatedAt($faker->dateTimeBetween($startDate = '-3 months', $endDate = 'now'));
            // If a task is finished the in progress is false.
            if($task->getFinished() == true) {
                $task->setCompletedBy($faker->words($nb = 2, $asText = true));
                $task->setInProgress(false);
            } else {
                $task->setInProgress($faker->boolean($chanceOfGettingTrue = 10));
                if($task->getInProgress() == true) {
                    $task->setIsWorking($faker->words($nb = 2, $asText = true));
                }
            }
            $this->addReference("Task-${taskCount}", $task);
            $em->flush(); // Persist all managed Entities.
        }
    }
}