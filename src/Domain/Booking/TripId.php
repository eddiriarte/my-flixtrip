<?php

declare(strict_types=1);

namespace App\Domain\Booking;

use EventSauce\EventSourcing\AggregateRootId;
use Ramsey\Uuid\Uuid;

class TripId implements AggregateRootId
{
    public function __construct(private readonly string $id)
    {
    }

    public function toString(): string
    {
        return $this->id;
    }

    public static function fromString(string $aggregateRootId): static
    {
        return new static($aggregateRootId);
    }

    public static function generate(): static
    {
        return new static((string) Uuid::uuid4());
    }
}
