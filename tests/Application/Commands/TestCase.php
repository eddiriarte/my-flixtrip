<?php

declare(strict_types=1);

namespace Tests\Application\Commands;

use App\Domain\Booking\Events\TripWasCreated;
use App\Domain\Booking\Trip;
use App\Domain\Booking\TripId;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\InMemoryMessageRepository;
use EventSauce\EventSourcing\MessageRepository;
use EventSauce\EventSourcing\TestUtilities\AggregateRootTestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Slim\App;
use Tests\ApplicationInstanceTrait;

class TestCase extends AggregateRootTestCase
{
    use ProphecyTrait;
    use ApplicationInstanceTrait;

    protected App $app;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app = $this->getAppInstance();

        // Store events in-memory...
        $this->app->getContainer()->set(MessageRepository::class, new InMemoryMessageRepository());
    }

    protected function newAggregateRootId(): AggregateRootId
    {
        return TripId::generate();
    }

    protected function aggregateRootClassName(): string
    {
        return Trip::class;
    }

    protected function handle(object $command)
    {
        if ($command instanceof Trip) {
            $this->repository->persist($command);
        }
    }
}
