<?php

declare(strict_types=1);

namespace App\Domain\Booking;

use Doctrine\Common\Collections\Collection;
use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;

class Trip implements AggregateRoot
{
    use AggregateRootBehaviour;

    private int $slots;
    private Collection $reservations;

    public function getSlots(): int
    {
        return $this->slots;
    }

    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public static function create(int $slots, ?string $origin = null, ?string $destiny = null): static
    {
        $trip = new static(TripId::generate());

        // record event!!

        return $trip;
    }
}
