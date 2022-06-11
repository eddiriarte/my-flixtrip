<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSourcing\Consumers;

use App\Application\Projections\ReadEntities\Reservation;
use App\Application\Projections\ReadEntities\Trip;
use App\Domain\Booking\Events\ReservationWasCancelled;
use App\Domain\Booking\Events\ReservationWasChanged;
use App\Domain\Booking\TripRepository;
use App\Domain\Booking\Events\ReservationWasPlaced;
use App\Domain\Booking\Events\TripWasCreated;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;

class TripProjection implements MessageConsumer
{
    public function __construct(
        private TripRepository $repository
    ) {
    }

    public function handle(Message $message): void
    {
        $event = $message->payload();

        match (true) { // @phpstan-ignore-line
            $event instanceof TripWasCreated => $this->persistNewTrip(
                $event,
                $message->aggregateRootId()->toString()
            ),
            $event instanceof ReservationWasPlaced => $this->persistNewReservation(
                $event,
                $message->aggregateRootId()->toString()
            ),
            $event instanceof ReservationWasCancelled => $this->removeExistingReservation(
                $event,
                $message->aggregateRootId()->toString()
            ),
            $event instanceof ReservationWasChanged => $this->changeExistingReservation(
                $event,
                $message->aggregateRootId()->toString()
            ),
        };
    }

    private function persistNewTrip(TripWasCreated $event, string $aggregateRootId): void
    {
        $this->repository->insert(
            new Trip(
                $aggregateRootId,
                $event->getOrigin(),
                $event->getDestination(),
                $event->getSlots(),
                $event->getSlots()
            )
        );
    }

    private function persistNewReservation(ReservationWasPlaced $event, string $aggregateRootId): void
    {
        $trip = $this->repository->get($aggregateRootId);
        $trip->addReservation(new Reservation(
            $event->getId(),
            $event->getCustomer(),
            $event->getSlots(),
            $trip
        ));

        $this->repository->update($trip);
    }

    private function removeExistingReservation(ReservationWasCancelled $event, string $aggregateRootId): void
    {
        $trip = $this->repository->get($aggregateRootId);
        $reservation = $trip->getReservations()
            ->filter(fn (Reservation $r) => $r->getId() === $event->getReservationId())
            ->first();
        $trip->removeReservation($reservation);

        $this->repository->update($trip);
    }

    private function changeExistingReservation(ReservationWasChanged $event, string $aggregateRootId): void
    {
        $trip = $this->repository->get($aggregateRootId);

        /** @var Reservation $reservation */
        $reservation = $trip->getReservations()
            ->filter(fn (Reservation $r) => $r->getId() === $event->getReservationId())
            ->first();

        $trip->changeReservation(new Reservation(
            $reservation->getId(),
            $reservation->getCustomer(),
            $event->getSlots(),
            $reservation->getTrip()
        ));

        $this->repository->update($trip);
    }
}
