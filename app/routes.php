<?php

declare(strict_types=1);

use App\Application\Actions\Api as v1;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->group('/api/v1', function (Group $group) {
        $group->post('/trips', v1\CreateTrip::class);
        $group->get('/trips', v1\ShowTrips::class);
        $group->post('/trips/{tripId}/reservations', v1\PlaceReservation::class);
        $group->delete('/trips/{tripId}/reservations/{reservationId}', v1\CancelReservation::class);
        $group->put('/trips/{tripId}/reservations/{reservationId}', v1\ChangeReservation::class);
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });
};
