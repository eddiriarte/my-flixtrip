<?php

declare(strict_types=1);

namespace App\Domain\Booking\Events;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class ReservationWasCancelled implements SerializablePayload
{
    public function __construct(
        private readonly string $reservationId
    ) {
    }

    public function getReservationId(): string
    {
        return $this->reservationId;
    }

    public function toPayload(): array
    {
        return [
            'reservation_id' => $this->reservationId,
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new static($payload['reservation_id']);
    }
}
