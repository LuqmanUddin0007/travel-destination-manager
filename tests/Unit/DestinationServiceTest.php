<?php

namespace App\Tests\Unit;

use App\Entity\Destination;
use App\Repository\DestinationRepository;
use App\Service\DestinationService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class DestinationServiceTest extends TestCase
{
    private EntityManagerInterface $em;
    private DestinationRepository $repo;
    private DestinationService $service;

    protected function setUp(): void
    {
        $this->em      = $this->createMock(EntityManagerInterface::class);
        $this->repo    = $this->createMock(DestinationRepository::class);
        $this->service = new DestinationService($this->em, $this->repo);
    }

    public function testCreateDestinationSuccessfully(): void
    {
        $this->em->expects($this->once())->method('persist');
        $this->em->expects($this->once())->method('flush');

        $dest = $this->service->create([
            'name'               => 'Bali',
            'activities'         => ['diving', 'hiking'],
            'average_cost'       => 1200,
            'best_travel_months' => ['June', 'July'],
        ]);

        $this->assertInstanceOf(Destination::class, $dest);
        $this->assertEquals('Bali', $dest->getName());
        $this->assertEquals(1200, $dest->getAverageCost());
        $this->assertContains('diving', $dest->getActivities());
        $this->assertContains('June', $dest->getBestTravelMonths());
    }

    public function testCreateThrowsIfNameMissing(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->service->create(['average_cost' => 1200]);
    }

    public function testCreateThrowsIfCostMissing(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->service->create(['name' => 'Paris']);
    }

    public function testGetByIdReturnsDestination(): void
    {
        $dest = new Destination();
        $dest->setName('Tokyo');
        $dest->setAverageCost(2000);
        $dest->setActivities(['culture']);
        $dest->setBestTravelMonths(['March']);

        $this->repo->method('find')->willReturn($dest);

        $result = $this->service->getById(1);
        $this->assertEquals('Tokyo', $result->getName());
    }

    public function testGetByIdReturnsNullIfNotFound(): void
    {
        $this->repo->method('find')->willReturn(null);
        $this->assertNull($this->service->getById(999));
    }

    public function testGetAllReturnsArray(): void
    {
        $this->repo->method('findAll')->willReturn([]);
        $this->assertIsArray($this->service->getAll());
    }

    public function testUpdateDestination(): void
    {
        $dest = new Destination();
        $dest->setName('Old Name');
        $dest->setAverageCost(500);
        $dest->setActivities([]);
        $dest->setBestTravelMonths([]);

        $this->em->expects($this->once())->method('flush');

        $updated = $this->service->update($dest, [
            'name'         => 'New Name',
            'average_cost' => 999,
        ]);

        $this->assertEquals('New Name', $updated->getName());
        $this->assertEquals(999, $updated->getAverageCost());
    }

    public function testDeleteDestination(): void
    {
        $dest = new Destination();
        $dest->setName('Bali');
        $dest->setAverageCost(1200);
        $dest->setActivities([]);
        $dest->setBestTravelMonths([]);

        $this->em->expects($this->once())->method('remove')->with($dest);
        $this->em->expects($this->once())->method('flush');

        $this->service->delete($dest);
    }

    public function testSearchDelegatesToRepository(): void
    {
        $this->repo->expects($this->once())
            ->method('findByFilters')
            ->with('diving', 1500.0, 'June')
            ->willReturn([]);

        $result = $this->service->search('diving', 1500.0, 'June');
        $this->assertIsArray($result);
    }
}