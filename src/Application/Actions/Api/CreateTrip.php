<?php

declare(strict_types=1);

namespace App\Application\Actions\Api;

use App\Application\Actions\JsonResponse;
use App\Application\Exceptions\ValidationException;
use App\Domain\Booking\Trip;
use App\Domain\Booking\TripId;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rakit\Validation\Validator;

class CreateTrip
{
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $data = $this->validated($request->getParsedBody());

        $trip = Trip::new(TripId::generate())
            ->initialize(
                $data['slots'],
                $data['origin'] ?? null,
                $data['destination'] ?? null
            );

        return (new JsonResponse($response))->send([
            'trip_id' => $trip->aggregateRootId()->toString(),
            'slots' => $trip->getSlots(),
            'origin' => $trip->getOrigin(),
            'destination' => $trip->getDestination(),
        ]);
    }

    private function validated(array $parameters): array
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
