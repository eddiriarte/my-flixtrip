<?php

declare(strict_types=1);

namespace App\Domain\Booking\Validators;

use App\Application\Exceptions\ValidationException;
use Rakit\Validation\Validator;

class TripValidator
{
    public function validate(array $parameters): array
    {
        $validation = (new Validator())
            ->validate(
                $parameters,
                [
                    'origin' => 'nullable|alpha_spaces',
                    'destination' => 'nullable|alpha_spaces',
                    'slots' => 'required|integer',
                ]
            );

        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        return $validation->getValidData();
    }
}
