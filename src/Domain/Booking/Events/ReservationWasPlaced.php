<?php

declare(strict_types=1);

namespace App\Domain\Booking\Events;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class ReservationWasPlaced implements SerializablePayload
{
    public function __construct(
        private readonly string $id,
        private readonly int $slots,
        private readonly string $customer,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSlots(): int
    {
        return $this->slots;
    }

    public function getCustomer(): string
    {
        return $this->customer;
    }

    public function toPayload(): array
    {
        return [
            'id' => $this->id,
            'slots' => $this->slots,
            'customer' => $this->customer,
        ];
    }

    public static function fromPayload(array $payload): static
    {
        return new static(
            $payload['id'],
            $payload['slots'],
            $payload['customer']
        );
    }
}
