#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\Data\DriverStorage;
use App\Data\Flight;
use App\Data\FlightStorage;
use App\Exception\DriverNotFoundException;
use App\Exception\FlightNotFoundException;
use App\RacingCommand;
use Symfony\Component\Console\Application;

$app = new Application('Racing Report', 'v1.0.0');

$app->add(new RacingCommand());
try {
    $app->run();
} catch (DriverNotFoundException|FlightNotFoundException) {
} finally {
   return 'There are neither driver nor flight you trying to find';
}
