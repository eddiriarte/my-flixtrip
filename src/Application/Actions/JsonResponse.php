<?php

declare(strict_types=1);

namespace App\Application\Actions;

use Psr\Http\Message\ResponseInterface;

class JsonResponse
{
    public function __construct(private ResponseInterface $response)
    {
    }

    public function withHeader(string $name, string $value): static
    {
        $this->response->withHeader($name, $value);

        return $this;
    }

    public function send(array|int|string|null $data = null, int $statusCode = 200): ResponseInterface
    {
        $payload = json_encode(new ActionPayload($statusCode, $data));

        $this->response->getBody()->write($payload);

        return $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}
