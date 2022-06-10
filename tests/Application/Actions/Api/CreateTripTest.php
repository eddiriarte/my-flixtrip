<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Api;

use App\Application\Exceptions\ValidationException;
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
                'destination' => 'Munich',
            ]);
        $response = $app->handle($request);

        $payload = json_decode((string)$response->getBody(), true);
        unset($payload['data']['trip_id']); // TODO: find a way to mock uuid or use UUIDv5

        $expectedPayload = [
            'statusCode' => 200,
            'data' => [
                'origin' => 'Berlin',
                'destination' => 'Munich',
                'slots' => 10,
            ],
        ];

        $this->assertEquals($expectedPayload, $payload);
    }

    public function testInvokedActionReturnsValidationError()
    {
        $app = $this->getAppInstance();

        $this->container = $app->getContainer();

        $request = $this->createTestRequest('POST', '/api/v1/trips')
            ->withParsedBody([
                'destiny' => 'Munich',
            ]);

        $this->expectExceptionCode(422);
        $this->expectException(ValidationException::class);

        $response = $app->handle($request);
    }
}
