<?php

declare(strict_types=1);

namespace App\Domain\Booking;

class Reservation
{
    public function __construct(
        private readonly string $id,
        private readonly int $slots,
        private readonly Customer $customer,
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

    public function getCustomer(): Customer
    {
        return $this->customer;
    }
}
