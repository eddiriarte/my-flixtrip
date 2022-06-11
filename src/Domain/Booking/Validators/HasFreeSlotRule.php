<?php

declare(strict_types=1);

namespace App\Domain\Booking\Validators;

use App\Domain\Booking\TripRepository;
use Rakit\Validation\Rule;

class HasFreeSlotRule extends Rule
{
    protected $message = "Not enough :attribute (:value) available at the moment.";

    protected $fillableParams = ['trip_id'];

    public function __construct(
        private readonly TripRepository $repository
    ) {
    }

    public function check($value): bool
    {
        $this->requireParameters(['trip_id']);
        $tripId = $this->parameter('trip_id');

        return $this->repository->hasSlotsAvailable($tripId, (int)$value);
    }
}
