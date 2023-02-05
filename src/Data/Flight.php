<?php

declare(strict_types=1);

namespace App\Data;

use DateTimeImmutable;

class Flight
{
    private string $driverId;
    private string $driverName;
    private string $team;
    private DateTimeImmutable $start;
    private DateTimeImmutable $finish;
    private string $duration;

    public function __construct(string $driverId, string $driverName, string $team, DateTimeImmutable $start)
    {
        $this->driverId = $driverId;
        $this->driverName = $driverName;
        $this->team = $team;
        $this->start = $start;
    }

    public function getDriverId(): string
    {
        return $this->driverId;
    }

    public function getDriverName(): string
    {
        return trim($this->driverName);
    }

    public function getTeam(): string
    {
        return $this->team;
    }

    public function getStart(): DateTimeImmutable
    {
        return $this->start;
    }

    public function setStart(DateTimeImmutable $start): Flight
    {
        $this->start = $start;
        return $this;
    }

    public function getFinish(): DateTimeImmutable
    {
        return $this->finish;
    }

    public function setFinish(DateTimeImmutable $finish): Flight
    {
        $this->finish = $finish;
        return $this;
    }

    public function getDuration(DateTimeImmutable $start, DateTimeImmutable $finish): string
    {
        $minutes = $start->diff($finish)->format('%I');
        $seconds = $start->diff($finish)->format('%S');
        $microseconds = str_pad(strval((int)($start->diff($finish)->format('%f')) / 1000),3,'0',STR_PAD_LEFT);
        $this->duration = (string)$minutes  . ':' . $seconds . '.' . $microseconds;

        return $this->duration;
    }

    public function getDurationInt(DateTimeImmutable $start, DateTimeImmutable $finish): int
    {
        $minutes = (int)($start->diff($finish)->format('%I%S')) * 1000000;
        $seconds = (int)($start->diff($finish)->format('%f'));
        $microseconds = intval($minutes + $seconds) / 1000;
        return $microseconds;
    }
}
