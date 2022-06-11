<?php

declare(strict_types=1);

namespace App\Application\Actions\Api;

use App\Application\Actions\JsonResponse;
use App\Application\Commands\CancelReservationCommand;
use EventSauce\EventSourcing\AggregateRootRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CancelReservation
{
    public function __construct(
        private CancelReservationCommand $command
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
        ];

        $this->command->handle($values);

        return (new JsonResponse($response))
            ->send('Reservation was cancelled!');
    }
}
