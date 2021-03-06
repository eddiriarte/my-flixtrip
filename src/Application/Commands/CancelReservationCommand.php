<?php

declare(strict_types=1);

namespace App\Application\Commands;

use App\Application\Projections\ReadEntities\Reservation;
use App\Domain\Booking\Trip;
use App\Domain\Booking\TripId;
use App\Domain\Booking\Validators\ReservationExistenceValidator;
use EventSauce\EventSourcing\AggregateRootRepository;

class CancelReservationCommand
{
    public function __construct(
        private ReservationExistenceValidator $validator,
        private AggregateRootRepository $rootRepository
    ) {
    }

    public function handle(array $data): Trip
    {
        $validated = $this->validator->validate($data);

        /** @var Trip $trip */
        $trip = $this->rootRepository
            ->retrieve(TripId::fromString($validated['trip_id']));

        $trip->cancelReservation($validated['reservation_id']);

        $this->rootRepository->persist($trip);

        return $trip;
    }
}
