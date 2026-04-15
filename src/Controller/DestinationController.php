<?php

namespace App\Controller;

use App\Service\DestinationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/destinations')]
class DestinationController extends AbstractController
{
    public function __construct(private DestinationService $service) {}

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $dest = $this->service->create($data);
            return $this->json($dest->toArray(), 201);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $all = $this->service->getAll();
        return $this->json(array_map(fn($d) => $d->toArray(), $all));
    }

    #[Route('/search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $results = $this->service->search(
            $request->query->get('activity'),
            $request->query->get('max_budget') !== null ? (float) $request->query->get('max_budget') : null,
            $request->query->get('travel_month')
        );

        return $this->json(array_map(fn($d) => $d->toArray(), $results));
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $dest = $this->service->getById($id);
        if (!$dest) {
            return $this->json(['error' => 'Destination not found'], 404);
        }
        return $this->json($dest->toArray());
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $dest = $this->service->getById($id);
        if (!$dest) {
            return $this->json(['error' => 'Destination not found'], 404);
        }

        $dest = $this->service->update($dest, json_decode($request->getContent(), true));
        return $this->json($dest->toArray());
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $dest = $this->service->getById($id);
        if (!$dest) {
            return $this->json(['error' => 'Destination not found'], 404);
        }

        $this->service->delete($dest);
        return $this->json(['message' => 'Deleted successfully']);
    }
}