<?php

declare(strict_types=1);

namespace App\Domain\Booking\Validators;

use App\Domain\Booking\TripRepository;
use Rakit\Validation\Rule;

class ReservationExistsRule extends Rule
{
    protected $message = ':attribute :value is not valid.';

    public function __construct(
        private readonly TripRepository $repository
    ) {
    }

    public function check($value): bool
    {
        return $this->repository->reservationExists($value);
    }
}
