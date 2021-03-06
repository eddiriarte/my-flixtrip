<?php

declare(strict_types=1);

namespace App\Domain\Booking\Validators;

use App\Application\Exceptions\ValidationException;
use Rakit\Validation\Validator;

class ReservationValidator
{
    public function __construct(private Validator $validator)
    {
    }

    public function validate(array $parameters): array
    {
        $validation = $this->validator
            ->validate(
                $parameters,
                [
                    'customer' => 'nullable|alpha_spaces',
                    'slots' => 'required|integer|free_slots:' . $parameters['trip_id'],
                    'trip_id' => 'required',
                ]
            );

        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        return $validation->getValidData();
    }
}
