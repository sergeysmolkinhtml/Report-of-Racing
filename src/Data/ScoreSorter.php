<?php

declare(strict_types=1);

namespace App\Data;

class ScoreSorter
{
    const UNDERLINE_DRIVERS = 16;
    private array $result;

    public function getResult(): array
    {
        return $this->result;
    }

    public function __construct(FlightStorage $data, bool $descending)
    {
        //flight data & its flights method
        $flightsData = $data->getFlights();

        $orderedArray = [];
        $flightsData->rewind();
        while ($flightsData->valid()) {
            $flight = $flightsData->current();
            $duration = $flight->getDurationInt($flight->getStart(), $flight->getFinish());
            $orderedArray[] = ['driver' => $flight->getDriverId(), 'score' => $duration, 'lined' => false];
            $flightsData->next();
        }

        if ($descending) {
            $lineNumber = count($orderedArray) - self::UNDERLINE_DRIVERS;
            usort($orderedArray, function ($a, $b) {
                return ($b['score'] - $a['score']);
            });
        } else {
            $lineNumber = 14;
            usort($orderedArray, function ($a, $b) {
                return ($a['score'] - $b['score']);
            });
        }
        $orderedArray[$lineNumber]['lined'] = true;
        $this->result = $orderedArray;
    }
}
