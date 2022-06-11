<?php

declare(strict_types=1);

namespace App\Domain\Booking\Events;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class ReservationWasChanged implements SerializablePayload
{
    public function __construct(
        private readonly string $reservationId,
        private readonly int $slots
    ) {
    }

    public function getReservationId(): string
    {
        return $this->reservationId;
    }

    public function getSlots(): int
    {
        return $this->slots;
    }

    public function toPayload(): array
    {
        return [
            'reservation_id' => $this->reservationId,
            'slots' => $this->slots,
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new static($payload['reservation_id'], $payload['slots']);
    }
}
