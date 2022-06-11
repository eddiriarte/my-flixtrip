<?php

declare(strict_types=1);

namespace App\Domain\Booking\Events;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class TripWasCreated implements SerializablePayload
{
    public function __construct(
        private readonly int $slots,
        private readonly ?string $origin = null,
        private readonly ?string $destination = null,
    ) {
    }

    public function getSlots(): int
    {
        return $this->slots;
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function toPayload(): array
    {
        return [
            'slots' => $this->slots,
            'origin' => $this->origin,
            'destination' => $this->destination,
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new static(
            $payload['slots'],
            $payload['origin'] ?? null,
            $payload['destination'] ?? null
        );
    }
}
