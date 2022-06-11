<?php

declare(strict_types=1);

namespace App\Application\Projections\ReadEntities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'reservations')]
class Reservation
{
    public function __construct(
        #[
            ORM\Id,
        ORM\Column(type: 'string')
        ]
        private string $id,
        #[ORM\Column(type: 'string')]
        private string $customer,
        #[ORM\Column(type: 'integer', nullable: true)]
        private int $slots,
        #[
            ORM\ManyToOne(targetEntity: Trip::class, inversedBy: 'reservations'),
        ORM\JoinColumn(name: 'trip_id', referencedColumnName: 'id')
        ]
        private ?Trip $trip
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCustomer(): string
    {
        return $this->customer;
    }

    public function getReservedSlots(): int
    {
        return $this->slots;
    }

    public function getTrip(): ?Trip
    {
        return $this->trip;
    }

    public function setTrip(): static
    {
        $this->trip = null;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'customer' => $this->customer,
            'slots' => $this->slots,
            'trip' => $this->trip,
        ];
    }
}
