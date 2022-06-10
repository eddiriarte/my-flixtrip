<?php

declare(strict_types=1);

namespace App\Application\Projections\ReadEntities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'trips')]
class Trip
{
    public function __construct(
        #[
            ORM\Id,
        ORM\Column(type: 'string')
        ]
        private string $id,
        #[ORM\Column(type: 'string', nullable: true)]
        private ?string $origin,
        #[ORM\Column(type: 'string', nullable: true)]
        private ?string $destiny,
        #[ORM\Column(type: 'integer')]
        private int $slots,
        #[ORM\Column(type: 'integer', name: 'free_slots')]
        private int $freeSlots,
        #[ORM\OneToMany(mappedBy: 'trip', targetEntity: Reservation::class, cascade: ['persist'])]
        private Collection $reservations = new ArrayCollection()
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getOrigin(): string
    {
        return $this->origin;
    }

    public function getDestiny(): string
    {
        return $this->destiny;
    }

    public function getTotalSlots(): int
    {
        return $this->slots;
    }

    public function getFreeSlots(): int
    {
        return $this->freeSlots;
    }

    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        $this->reservations->add($reservation);
        $this->freeSlots -= $reservation->getReservedSlots();

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        $this->reservations->remove($reservation);
        $this->freeSlots += $reservation->getReservedSlots();

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'origin' => $this->origin,
            'destiny' => $this->destiny,
            'slots' => $this->slots,
            'free_slots' => $this->freeSlots,
            'reservations' => $this->reservations
                ->map(fn (Reservation $reservation) => $reservation->toArray())
                ->toArray()
        ];
    }
}
