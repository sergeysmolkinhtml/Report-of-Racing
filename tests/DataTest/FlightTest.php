<?php

namespace App\Test;


use App\Data\Driver;
use App\Data\DriverStorage;
use App\Data\Flight;
use App\Data\FlightStorage;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SplObjectStorage;

class FlightTest extends TestCase
{
    protected SplObjectStorage $drivers;
    protected DriverStorage $driverStorage;

    protected FlightStorage $flightsStorage;
    protected Flight $flight;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->drivers = new SplObjectStorage();
        $this->driverStorage = new DriverStorage();
        $this->flightsStorage = new FlightStorage();

        parent::__construct($name, $data, $dataName);
    }

    public function testGettingFlightData()
    {
        //'d-m','i:s:f'
        $time = new \DateTime('12:02:49.914');
        $dateImm = DateTimeImmutable::createFromMutable($time);
        $output = (new Flight('NHR','Nico Hulkenberg','Renault', start: $dateImm));

        self::assertEquals('Renault',$output->getTeam());
        self::assertEquals('NHR',$output->getDriverId());
        self::assertEquals('Nico Hulkenberg',$output->getDriverName());
        self::assertEquals($time,$output->getStart());
    }
    public function testFindingAFlight()
    {
        $this->driverStorage->addDriver(new Driver('NHR','Nico Hulkenberg','Renault'));
        $this->flightsStorage->setDrivers($this->driverStorage);

        $start = new DateTimeImmutable('2018-05-24 12:02:49.914');
        $finish = new DateTimeImmutable('2018-05-24 13:04:02.979');

        $this->flight = (new Flight('NHR','Nico Hulcenberg','Renault',$start));
        $this->flight->setFinish($finish);

        $this->flightsStorage->addFlightStart('NHR',$start);
        $this->flightsStorage->addFlightFinish('NHR',$finish);

        self::assertEquals('Nico Hulkenberg',$this->flightsStorage->findByName('Nico Hulkenberg')->getDriverName());
    }
}
