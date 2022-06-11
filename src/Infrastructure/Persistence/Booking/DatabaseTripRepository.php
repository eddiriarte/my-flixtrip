<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Booking;

use App\Application\Projections\ReadEntities\Trip;
use App\Domain\Booking\TripRepository;
use Doctrine\ORM\EntityManager;

class DatabaseTripRepository implements TripRepository
{
    public function __construct(private EntityManager $entityManager)
    {
    }

    public function findAll(): array
    {
        return $this->entityManager
            ->getRepository(Trip::class)
            ->findAll();
    }

    public function insert(Trip $trip): void
    {
        $this->entityManager->persist($trip);
        $this->entityManager->flush();
    }

    public function get(string $tripId): Trip
    {
        return $this->entityManager
            ->getRepository(Trip::class)
            ->find($tripId);
    }

    public function update(Trip $trip): void
    {
        $this->entityManager->persist($trip);
        $this->entityManager->flush();
    }

    public function hasSlotsAvailable(string $tripId, int $slots): bool
    {
        $freeSlots = $this->entityManager
            ->createQueryBuilder()
            ->select('t.free_slots')
            ->from(Trip::class, 't')
            ->where('t.id = :tripId')
            ->setParameter('tripId', $tripId)
            ->getQuery()
            ->getSingleScalarResult();

        return $slots >= $freeSlots;
    }
}
