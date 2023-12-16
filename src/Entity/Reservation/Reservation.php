<?php

declare(strict_types=1);

namespace App\Entity\Reservation;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\TimestampableTrait;

final class Reservation implements ReservationInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $id;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $reservationDate;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReservationDate(): ?\DateTime
    {
        return $this->reservationDate;
    }

    public function setReservationDate(?\DateTime $reservationDate): void
    {
        $this->reservationDate = $reservationDate;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
