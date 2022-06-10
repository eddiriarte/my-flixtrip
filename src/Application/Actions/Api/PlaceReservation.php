<?php

declare(strict_types=1);

namespace App\Application\Actions\Api;

use App\Application\Actions\JsonResponse;
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
        private AggregateRootRepository $rootRepository
    ) {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $data = $this->validated($request->getParsedBody());

        /** @var Trip $trip */
        $trip = $this->rootRepository->retrieve(TripId::fromString($args['tripId']));

        $reservationId = (string)Uuid::uuid4();
        $trip->placeReservation(
            $reservationId,
            $data['slots'],
            $data['customer']
        );

        /** @var Reservation $reservation */
        $reservation = $trip->getReservations()
            ->filter(fn (Reservation $r) => $r->getId() === $reservationId)
            ->first();

        return (new JsonResponse($response))
            ->send([
                'trip_id' => $trip->aggregateRootId()->toString(),
                'reservation_id' => $reservation->getId(),
                'slots' => $reservation->getSlots(),
                'customer' => $reservation->getCustomer()->getName(),
            ]);
    }

    private function validated(array $data): array
    {
        return $data;
    }
}
