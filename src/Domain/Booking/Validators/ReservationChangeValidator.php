<?php

declare(strict_types=1);

namespace App\Domain\Booking\Validators;

use App\Application\Exceptions\ValidationException;
use Rakit\Validation\Validator;

class ReservationChangeValidator
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
                    'reservation_id' => 'required|reservation_exists',
                    'trip_id' => 'required',
                    'slots' => [
                        'required',
                        'integer',
                        'free_slots:' . $parameters['trip_id'] . ',' . $parameters['reservation_id']
                    ],
                ]
            );

        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        return $validation->getValidData();
    }
}
