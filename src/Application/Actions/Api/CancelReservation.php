<?php

declare(strict_types=1);

namespace App\Application\Actions\Api;

use App\Application\Actions\JsonResponse;
use EventSauce\EventSourcing\AggregateRootRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CancelReservation
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

        return (new JsonResponse($response))->send();



        /** @var Trip $trip */
        $trip = $this->rootRepository->retrieve(TripId::fromString($args['tripId']));

        $trip->cancelReservation($args['reservationId']);

        $this->rootRepository->persist($trip);

        $response->getBody()->write('Reservation was cancelled!');

        return $response;
    }
}
