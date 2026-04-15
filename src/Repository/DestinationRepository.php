<?php

namespace App\Repository;

use App\Entity\Destination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DestinationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Destination::class);
    }

    public function findByFilters(?string $activity, ?float $maxBudget, ?string $month): array
    {
        $qb = $this->createQueryBuilder('d');

        if ($maxBudget !== null) {
            $qb->andWhere('d.average_cost <= :budget')
               ->setParameter('budget', $maxBudget);
        }

        $results = $qb->getQuery()->getResult();

        if ($activity) {
            $results = array_filter(
                $results,
                fn($d) => in_array($activity, $d->getActivities())
            );
        }

        if ($month) {
            $results = array_filter(
                $results,
                fn($d) => in_array($month, $d->getBestTravelMonths())
            );
        }

        return array_values($results);
    }
}