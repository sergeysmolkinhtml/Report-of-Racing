<?php

declare(strict_types=1);

namespace App\Reporter;

use App\Data\FlightStorage;
use App\Data\ScoreSorter;
use Symfony\Component\Console\Output\OutputInterface;

class CliReporter
{
    public function print(FlightStorage $data, OutputInterface $output, bool $descending): void
    {

        $orderedArray = new ScoreSorter($data, $descending);
        $orderedDrivers = $orderedArray->getResult();

        foreach ($orderedDrivers as $key => $ordered) {
            $index = $ordered['driver'];
            $flight = $data->find($index);
            $output->write(str_pad(strval($key + 1), 2, ' ', STR_PAD_LEFT) . '. ');
            $output->write(str_pad($flight->getDriverName(), 20) . '| ');
            $output->write(str_pad($flight->getTeam(), 30) . ' | ');
            $output->writeln('<fg=#c0392b>' . $flight->getDuration($flight->getStart(), $flight->getFinish()) . '</>');
            if ($ordered['lined']) {
                $output->writeln('--------------------------------------------------------------------');
            }
        }
    }

    public function printOne(FlightStorage $data, OutputInterface $output, string $driverName): void
    {
        $flight = $data->findByName($driverName);
        $output->writeln('Driver : ' . str_pad($flight->getDriverName(), 20));
        $output->writeln(str_pad('Team : ' . $flight->getTeam(), 30));
        $output->writeln('start :  <fg=green>' . str_pad((string)$flight->getStart(), 26) . '</>');
        $output->writeln('finish : ' . str_pad((string)$flight->getFinish(), 26) . ' ');
        $output->writeln('time : <fg=#c0392b>' . $flight->getDuration($flight->getStart(), $flight->getFinish()) . '</>');
    }

}
