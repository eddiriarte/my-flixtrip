<?php

namespace App\Application\Actions\Api;

use App\Application\Actions\JsonResponse;
use App\Application\Projections\ReadEntities\Trip;
use App\Domain\Booking\TripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ShowTrips
{
    public function __construct(
        private TripRepository $repository
    ) {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $trips = (new ArrayCollection($this->repository->findAll()))
            ->map(fn(Trip $trip) => $trip->toArray())
            ->toArray();

        return (new JsonResponse($response))->send($trips);
    }
}
