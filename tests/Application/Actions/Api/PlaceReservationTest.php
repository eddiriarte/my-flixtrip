<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Api;

use App\Application\Exceptions\ValidationException;
use App\Domain\Booking\TripRepository;
use EventSauce\EventSourcing\InMemoryMessageRepository;
use EventSauce\EventSourcing\MessageRepository;
use Psr\Container\ContainerInterface;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class PlaceReservationTest extends TestCase
{
    public ContainerInterface $container;

    public function testInvokedAction()
    {
        $app = $this->getAppInstance();
        $tripId = (string) Uuid::uuid4();

        $container = $app->getContainer();
        $tripRepository = $this->getMockBuilder(TripRepository::class)->getMock();
        $tripRepository
            ->method('hasSlotsAvailable')
            ->with($tripId, 10)
            ->willReturn(true);
        $container->set(TripRepository::class, $tripRepository);
        $container->set(MessageRepository::class, new InMemoryMessageRepository());

        $request = $this->createTestRequest('POST', '/api/v1/trips/' . $tripId . '/reservations')
            ->withParsedBody([
                'slots' => 10,
                'customer' => 'Ron'
            ]);
        $response = $app->handle($request);

        $payload = json_decode((string)$response->getBody(), true);
        unset($payload['data']['reservation_id']); // TODO: find a way to mock uuid or use UUIDv5

        $expectedPayload = [
            'statusCode' => 201,
            'data' => [
                'trip_id' => $tripId,
                'customer' => 'Ron',
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
