<?php

namespace AppBundle\Repository;

/**
 * TimerSettingsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TimerSettingsRepository extends \Doctrine\ORM\EntityRepository
{
    public function getSettings($username){
        $q = $this->createQueryBuilder('ts')
            ->leftJoin('ts.user', 'u')
            ->leftJoin('ts.work_time', 'wt')
            ->leftJoin('ts.pause_time', 'pt')
            ->select('ts, wt, pt')
            ->where('u.username = :username')->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();
        return $q;
    }


    public function checkIfExists($username){
        $q = $this->createQueryBuilder('ts')
            ->leftJoin('ts.user', 'u')
            ->select('ts')
            ->where('u.username = :username')->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();

        return $q;
    }
}