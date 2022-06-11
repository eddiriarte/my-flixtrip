<?php

declare(strict_types=1);

namespace App\Application\Actions\Api;

use App\Application\Actions\JsonResponse;
use App\Application\Commands\CancelReservationCommand;
use App\Application\Commands\ChangeReservationCommand;
use EventSauce\EventSourcing\AggregateRootRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ChangeReservation
{
    public function __construct(
        private ChangeReservationCommand $command
    ) {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $values = [
            'trip_id' => $args['tripId'],
            'reservation_id' => $args['reservationId'],
        ] + $request->getParsedBody();

        $reservation = $this->command->handle($values);

        return (new JsonResponse($response))
            ->send([
                'trip_id' => $args['tripId'],
                'reservation_id' => $reservation->getId(),
                'slots' => $reservation->getSlots(),
                'customer' => $reservation->getCustomer()->getName(),
            ]);
    }
}
