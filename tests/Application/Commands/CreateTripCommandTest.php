<?php

declare(strict_types=1);

namespace Tests\Application\Commands;

use App\Domain\Booking\Events\TripWasCreated;
use App\Domain\Booking\Trip;
use EventSauce\EventSourcing\InMemoryMessageRepository;
use EventSauce\EventSourcing\MessageRepository;

class CreateTripCommandTest extends TestCase
{
    public function testHandleCreateTipCommand()
    {
        $tripId = $this->aggregateRootId();

        $this->when(Trip::new($tripId)->initialize(10))
            ->then(new TripWasCreated(10));
    }

    public function testHandleCreateTipCommandWithOriginAndDestination()
    {
        $app = $this->getAppInstance();

        $container = $app->getContainer();
        $container->set(MessageRepository::class, new InMemoryMessageRepository());

        $tripId = $this->aggregateRootId();

        $this->when(Trip::new($tripId)->initialize(10, 'Berlin', 'Munich'))
            ->then(new TripWasCreated(10, 'Berlin', 'Munich'));
    }
}
