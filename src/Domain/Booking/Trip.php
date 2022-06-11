<?php

declare(strict_types=1);

namespace App\Domain\Booking;

use App\Domain\Booking\Events\ReservationWasPlaced;
use App\Domain\Booking\Events\TripWasCreated;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;

class Trip implements AggregateRoot
{
    use AggregateRootBehaviour;

    private int $slots;
    private ?string $origin = null;
    private ?string $destination = null;
    private Collection $reservations;

    public function getSlots(): int
    {
        return $this->slots;
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public static function new(TripId $id): static
    {
        return new static($id);
    }

    public function initialize(int $slots, ?string $origin = null, ?string $destination = null): static
    {
        $this->recordThat(new TripWasCreated($slots, $origin, $destination));

        return $this;
    }

    public function applyTripWasCreated(TripWasCreated $event): void
    {
        $this->slots = $event->getSlots();
        $this->origin = $event->getOrigin();
        $this->destination = $event->getDestination();
        $this->reservations = new ArrayCollection();
    }

    public function placeReservation(string $reservationId, int $slots, ?string $customer): static
    {
        $this->recordThat(new ReservationWasPlaced($reservationId, $slots, $customer));

        return $this;
    }

    public function applyReservationWasPlaced(ReservationWasPlaced $event): void
    {
        $this->reservations->add(new Reservation(
            $event->getId(),
            $event->getSlots(),
            new Customer($event->getCustomer())
        ));
    }
}
