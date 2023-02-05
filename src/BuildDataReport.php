<?php

declare(strict_types=1);

namespace App;

use App\Data\Driver;
use App\Data\DriverStorage;
use App\Data\FlightStorage;
use DateTimeImmutable;
use Exception;

class BuildDataReport
{
    protected DriverStorage $drivers;
    protected FlightStorage $flights;

    public function buildReport(string $logsLocation): FlightStorage
    {
        $this->flights = new FlightStorage();

        $this->readDrivers($logsLocation . '/abbreviations.txt');
        $this->flights->setDrivers($this->drivers);
        $this->readLog('start', $logsLocation . '/start.log');
        $this->readLog('end', $logsLocation . '/end.log');
        return $this->flights;
    }

    private function readDrivers(string $dataFile): void
    {
        $txtFile = file_get_contents($dataFile);
        $rows = explode("\n", $txtFile);
        $driversData = new DriverStorage();
        foreach ($rows as $data) {
            $index = substr($data, 0, 3);
            $name = substr($data, 4, 26);
            $team = substr($data, 30, 25);
            $driversData->addDriver(new Driver($index, $name, $team));
        }
        $this->drivers = $driversData;
    }

    /**
     * @throws Exception
     */
    private function readLog(string $typeLog, string $dataFile): void
    {
        $txtFile = file_get_contents($dataFile);
        $rows = explode("\n", $txtFile);
        foreach ($rows as $data) {
            $pattern = '/(...)([0-9]{4})-([0-9]{2})-([0-9]{2})_([0-9]{2}):([0-9]{2}):([0-9]{2})\.([0-9]{3})/';
            if (preg_match($pattern, $data, $matches)) {
                $index = $matches[1];
                $year = $matches[2];
                $month = $matches[3];
                $day = $matches[4];
                $hours = $matches[5];
                $minutes = $matches[6];
                $seconds = $matches[7];
                $milliseconds = $matches[8];
            }
            $dataString = "$year-$month-$day";
            $timeString = "$hours:$minutes:$seconds.$milliseconds";

            if ($typeLog == 'start') {
                $start = new DateTimeImmutable($dataString . ' ' . $timeString);
                $this->flights->addFlightStart($index, $start);
            }
            if ($typeLog == 'end') {
                if (trim($dataString) == '') {
                    $this->flights->dropFlight($index);
                } else {
                    $finish = new DateTimeImmutable($dataString . ' ' . $timeString);
                    $this->flights->addFlightFinish($index, $finish);
                }
            }
        }
    }
}