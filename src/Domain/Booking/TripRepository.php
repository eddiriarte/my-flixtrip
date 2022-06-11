<?php

declare(strict_types=1);

namespace App\Domain\Booking;

use App\Application\Projections\ReadEntities\Trip;

interface TripRepository
{
    public function findAll(): array;

    public function get(string $tripId): Trip;

    public function insert(Trip $trip): void;

    public function update(Trip $trip): void;

    public function hasSlotsAvailable(string $tripId, int $slots): bool;

    public function reservationExists(string $reservationId): bool;
}
