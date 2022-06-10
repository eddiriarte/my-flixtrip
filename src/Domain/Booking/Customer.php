<?php

declare(strict_types=1);

namespace App\Domain\Booking;

class Customer
{
    public function __construct(
        private readonly string $name
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
