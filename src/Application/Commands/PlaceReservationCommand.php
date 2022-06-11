<?php

declare(strict_types=1);

namespace App\Application\Commands;

use App\Domain\Booking\Reservation;
use App\Domain\Booking\Trip;
use App\Domain\Booking\TripId;
use App\Domain\Booking\Validators\ReservationValidator;
use EventSauce\EventSourcing\AggregateRootRepository;
use Ramsey\Uuid\Uuid;

class PlaceReservationCommand
{
    public function __construct(
        private ReservationValidator $validator,
        private AggregateRootRepository $rootRepository
    ) {
    }

    public function handle(array $data): Reservation
    {
        $validated = $this->validator->validate($data);

        /** @var Trip $trip */
        $trip = $this->rootRepository
            ->retrieve(TripId::fromString($validated['trip_id']));

        $reservationId = (string)Uuid::uuid4();
        $trip->placeReservation(
            $reservationId,
            $validated['slots'],
            $validated['customer']
        );

        $this->rootRepository->persist($trip);

        return $trip->getReservations()
            ->filter(fn (Reservation $r) => $r->getId() === $reservationId)
            ->first();
    }
}
