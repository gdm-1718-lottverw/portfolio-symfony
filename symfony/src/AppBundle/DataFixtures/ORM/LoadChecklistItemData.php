<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ChecklistItem;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Faker;
use AppBundle\Services\HashCode;
/**
 * Class LoadChecklistItemData.
 *
 * @author Lotte Verwerft
 */
class LoadChecklistItemData extends AbstractFixture implements OrderedFixtureInterface {

    const COUNT = 50;

    /**
     * {@inheritdoc}
     */

    public function getOrder(){
        // Load this file second.
        return 2;
    }

    /**
     * {@inheritdoc}
     */

    public function load(ObjectManager $em): void
    {
        $checklist = $em->getRepository('AppBundle:Checklist')->findAll();
        $totalChecklist = count($checklist);
        $hash =  new HashCode();
        for ($itemCount = 0; $itemCount < self::COUNT; ++$itemCount) {
            $locale = 'nl_BE';
            $faker = Faker::create($locale);
            $item = new ChecklistItem();
            $em->persist($item); // Manage Entity for persistence.
            $item
                ->setName($faker->words($nb = 3, $asText = true))
                ->setHash($hash->generateHashCode(10))
                ->setEstimatePomodoros($faker->numberBetween(1, 5))
                ->setDescription($faker->paragraph($nbSentences = 3, $variableNbSentences = true))
                ->setFinished($faker->boolean($chanceOfGettingTrue = 30))
                ->setChecklist($this->getReference('Checklist-'. rand(1, ($totalChecklist - 1))))
                ->setCreatedAt($faker->dateTimeBetween($startDate = '-3 months', $endDate = 'now'));
            $this->addReference("Item-${itemCount}", $item);
            if($item->getFinished() == true) {
                $item->setCompletedBy($faker->words($nb = 2, $asText = true));
                $item->setInProgress(false);
            } else {
                $item->setInProgress($faker->boolean($chanceOfGettingTrue = 10));
                if($item->getInProgress() == true) {
                    $item->setIsWorking($faker->words($nb = 2, $asText = true));
                }
            }
            $em->flush(); // Persist all managed Entities.
        }

    }
}














