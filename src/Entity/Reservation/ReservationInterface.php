<?php

declare(strict_types=1);

namespace App\Entity\Reservation;

interface ReservationInterface
{
    public function getId(): ?int;

    public function getStartDate(): \DateTime;

    public function setStartDate(\DateTime $startDate): void;

    public function getEndDate(): \DateTime;

    public function setEndDate(\DateTime $endDate): void;

    public function getReservedAt(): \DateTime;

    public function setReservedAt(\DateTime $reservedAt): void;

    public function getDuration(): int;

    public function setDuration(int $duration): void;

    public function getDescription(): ?string;

    public function setDescription(?string $description): void;

    public function getName(): string;

    public function setName(string $name): void;

    public function getReservationPeriod(): string;
}
