<?php

declare(strict_types=1);

namespace App\Application\Actions\Api;

use App\Application\Actions\JsonResponse;
use App\Application\Commands\PlaceReservationCommand;
use App\Domain\Booking\Customer;
use App\Domain\Booking\Reservation;
use App\Domain\Booking\Trip;
use App\Domain\Booking\TripId;
use EventSauce\EventSourcing\AggregateRootRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;

class PlaceReservation
{
    public function __construct(
        private PlaceReservationCommand $command
    ) {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $tripId = $args['tripId'];

        $values = $request->getParsedBody();
        $values['trip_id'] = $tripId;

        $reservation = $this->command->handle($values);

        return (new JsonResponse($response))
            ->send(
                [
                    'trip_id' => $tripId,
                    'reservation_id' => $reservation->getId(),
                    'slots' => $reservation->getSlots(),
                    'customer' => $reservation->getCustomer()->getName(),
                ],
                201
            );
    }
}
