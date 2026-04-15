<?php

namespace App\Entity;

use App\Repository\DestinationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DestinationRepository::class)]
#[ORM\Table(name: 'destinations')]
class Destination
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'json')]
    private array $activities = [];

    #[ORM\Column(type: 'float')]
    private float $average_cost;

    #[ORM\Column(type: 'json')]
    private array $best_travel_months = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getActivities(): array
    {
        return $this->activities;
    }

    public function setActivities(array $activities): void
    {
        $this->activities = $activities;
    }

    public function getAverageCost(): float
    {
        return $this->average_cost;
    }

    public function setAverageCost(float $cost): void
    {
        $this->average_cost = $cost;
    }

    public function getBestTravelMonths(): array
    {
        return $this->best_travel_months;
    }

    public function setBestTravelMonths(array $months): void
    {
        $this->best_travel_months = $months;
    }

    public function toArray(): array
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'activities'         => $this->activities,
            'average_cost'       => $this->average_cost,
            'best_travel_months' => $this->best_travel_months,
        ];
    }
}