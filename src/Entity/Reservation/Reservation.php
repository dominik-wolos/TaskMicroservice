<?php

declare(strict_types=1);

namespace App\Entity\Reservation;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'app_reservations')]
class Reservation implements ReservationInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $id;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private \DateTime $startDate;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private \DateTime $endDate;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private \DateTime $reservedAt;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $duration;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getReservedAt(): \DateTime
    {
        return $this->reservedAt;
    }

    public function setReservedAt(\DateTime $reservedAt): void
    {
        $this->reservedAt = $reservedAt;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getReservationPeriod(): string
    {
        return sprintf('%s - %s', $this->startDate->format('Y-m-d H:i'), $this->endDate->format('Y-m-d H:i'));
    }
}
