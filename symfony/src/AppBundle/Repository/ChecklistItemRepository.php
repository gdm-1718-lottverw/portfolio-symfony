<?php

namespace AppBundle\Repository;

/**
 * ChecklistItemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ChecklistItemRepository extends \Doctrine\ORM\EntityRepository
{
    public function getItemByHash($hash){
        $q = $this->createQueryBuilder('item')
            ->leftJoin('item.checklist', 'checklist')
            ->leftJoin('checklist.project', 'p')
            ->select('item.name, item.description, item.estimatePomodoros As pomodoro, p.title As project_title')
            ->where('item.hash = :hash')->setParameter('hash', $hash)
            ->getQuery()
            ->getArrayResult();
        
        return $q;
    }

    public function getChecklistItems ($project_hash){
        $q = $this->createQueryBuilder('i')
            ->leftJoin('i.checklist', 'c')
            ->leftJoin('c.project', 'p')
            ->select('i')
            ->where('p.hash = :hash')->setParameter('hash', $project_hash)
            ->getQuery()
            ->getArrayResult();

        return $q;
    }
     public function getItem($hash){
        $q = $this->createQueryBuilder('checklistItem')
            ->select('checklistItem')
            ->where('checklistItem.hash = :hash')->setParameter('hash', $hash)
            ->getQuery()
            ->getOneOrNullResult();
        
        return $q;
    }
}
