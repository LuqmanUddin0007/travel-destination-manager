<?php

namespace App\Service;

use App\Entity\Destination;
use App\Repository\DestinationRepository;
use Doctrine\ORM\EntityManagerInterface;

class DestinationService
{
    public function __construct(
        private EntityManagerInterface $em,
        private DestinationRepository $repository
    ) {}

    public function create(array $data): Destination
    {
        if (empty($data['name']) || !isset($data['average_cost'])) {
            throw new \InvalidArgumentException('name and average_cost are required');
        }

        $dest = new Destination();
        $dest->setName($data['name']);
        $dest->setActivities($data['activities'] ?? []);
        $dest->setAverageCost((float) $data['average_cost']);
        $dest->setBestTravelMonths($data['best_travel_months'] ?? []);

        $this->em->persist($dest);
        $this->em->flush();

        return $dest;
    }

    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    public function getById(int $id): ?Destination
    {
        return $this->repository->find($id);
    }

    public function update(Destination $dest, array $data): Destination
    {
        if (isset($data['name']))               $dest->setName($data['name']);
        if (isset($data['activities']))          $dest->setActivities($data['activities']);
        if (isset($data['average_cost']))        $dest->setAverageCost((float) $data['average_cost']);
        if (isset($data['best_travel_months']))  $dest->setBestTravelMonths($data['best_travel_months']);

        $this->em->flush();

        return $dest;
    }

    public function delete(Destination $dest): void
    {
        $this->em->remove($dest);
        $this->em->flush();
    }

    public function search(?string $activity, ?float $maxBudget, ?string $month): array
    {
        return $this->repository->findByFilters($activity, $maxBudget, $month);
    }
}