<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSourcing\Consumers;

use App\Application\Projections\TripRepository;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;

class TripProjection implements MessageConsumer
{
    public function __construct(
        private TripRepository $repository
    ) {
    }

    public function handle(Message $message): void
    {
        // TODO: Implement handle() method.
    }
}
