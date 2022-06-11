<?php

namespace App\Application\Commands;

use App\Domain\Booking\Trip;
use App\Domain\Booking\TripId;
use App\Domain\Booking\Validators\ExistingReservationValidator;
use EventSauce\EventSourcing\AggregateRootRepository;

class CancelReservationCommand
{
    public function __construct(
        private ExistingReservationValidator $validator,
        private AggregateRootRepository $rootRepository
    ) {
    }

    public function handle(array $data)
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
