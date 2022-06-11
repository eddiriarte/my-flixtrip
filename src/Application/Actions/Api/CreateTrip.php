<?php

declare(strict_types=1);

namespace App\Application\Actions\Api;

use App\Application\Actions\JsonResponse;
use App\Application\Commands\CreateTripCommand;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CreateTrip
{
    public function __construct(
        private CreateTripCommand $command
    ) {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $trip = $this->command->handle($request->getParsedBody());

        return (new JsonResponse($response))->send(
            [
                'trip_id' => $trip->aggregateRootId()->toString(),
                'slots' => $trip->getSlots(),
                'origin' => $trip->getOrigin(),
                'destination' => $trip->getDestination(),
            ],
            201
        );
    }
}
