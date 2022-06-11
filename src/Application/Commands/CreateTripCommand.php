<?php

declare(strict_types=1);

namespace App\Application\Commands;

use App\Domain\Booking\Trip;
use App\Domain\Booking\TripId;
use App\Domain\Booking\Validators\TripValidator;
use EventSauce\EventSourcing\AggregateRootRepository;

class CreateTripCommand
{
    public function __construct(
        private TripValidator $validator,
        private AggregateRootRepository $rootRepository
    ) {
    }

    public function handle(array $data): Trip
    {
        $validated = $this->validator->validate($data);

        $trip = Trip::new(TripId::generate())
            ->initialize(
                $validated['slots'],
                $validated['origin'] ?? null,
                $validated['destination'] ?? null
            );

        $this->rootRepository->persist($trip);

        return $trip;
    }
}
