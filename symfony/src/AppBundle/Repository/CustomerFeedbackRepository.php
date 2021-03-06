<?php

namespace AppBundle\Repository;

/**
 * CustomerFeedbackRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CustomerFeedbackRepository extends \Doctrine\ORM\EntityRepository
{
    public function getProjectFeedback($hash) {
        $q = $this->createQueryBuilder('feedback')
            ->leftJoin('feedback.project', 'p')
            ->where('p.hash = :hash')->setParameter('hash', $hash)
            ->select('feedback')
            ->orderBy('feedback.submittedAt', 'DESC')
            ->getQuery()
            ->getArrayResult();

        return $q;
    }
}
