<?php

namespace App\Application\Actions\Api;

use App\Application\Actions\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CreateTrip
{
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        return (new JsonResponse($response))->send('Hello World!');
    }
}
