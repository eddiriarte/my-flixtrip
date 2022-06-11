<?php

declare(strict_types=1);

namespace App\Application\Commands;

use App\Domain\Booking\Reservation;
use App\Domain\Booking\Trip;
use App\Domain\Booking\TripId;
use App\Domain\Booking\Validators\ReservationChangeValidator;
use EventSauce\EventSourcing\AggregateRootRepository;

class ChangeReservationCommand
{
    public function __construct(
        private ReservationChangeValidator $validator,
        private AggregateRootRepository $rootRepository
    ) {
    }

    public function handle(array $data): Reservation
    {
        $validated = $this->validator->validate($data);

        /** @var Trip $trip */
        $trip = $this->rootRepository
            ->retrieve(TripId::fromString($validated['trip_id']));

        $trip->changeReservation(
            $validated['reservation_id'],
            $validated['slots'],
        );

        $this->rootRepository->persist($trip);

        return $trip->getReservations()
            ->filter(fn (Reservation $r) => $r->getId() === $validated['reservation_id'])
            ->first();
    }
}
