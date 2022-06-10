<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Api;

use Psr\Container\ContainerInterface;
use Tests\TestCase;

class CreateTripTest extends TestCase
{
    public ContainerInterface $container;

    public function testInvokedAction()
    {
        $app = $this->getAppInstance();

        $this->container = $app->getContainer();

        $request = $this->createTestRequest('POST', '/api/v1/trips')
            ->withParsedBody([
                'slots' => 10,
                'origin' => 'Berlin',
                'destiny' => 'Munich',
            ]);
        $response = $app->handle($request);

        $payload = json_decode((string)$response->getBody(), true);
        $expectedPayload = [
            'statusCode' => 200,
            'data' => [
                'origin' => 'Berlin',
                'destiny' => 'Munich',
                'slots' => 10,
            ],
        ];

        $this->assertEquals($expectedPayload, $payload);
    }
}
