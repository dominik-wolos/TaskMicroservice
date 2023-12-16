<?php

declare(strict_types=1);

namespace App\Entity\Reservation;

use Sylius\Component\Resource\Model\ResourceInterface;

interface ReservationInterface extends ResourceInterface
{
    public function getReservationDate(): ?\DateTime;

    public function setReservationDate(?\DateTime $reservationDate): void;

    public function getDescription(): ?string;

    public function setDescription(?string $description): void;
}
