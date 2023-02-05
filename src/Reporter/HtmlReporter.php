<?php

declare(strict_types=1);

namespace App\Reporter;

use App\Data\FlightStorage;
use App\Data\ScoreSorter;
use Symfony\Component\Console\Output\OutputInterface;

class HtmlReporter
{
    public function print(FlightStorage $data, string $logsLocation, bool $descending): void
    {
        $htmlReportFileName = $logsLocation . '/report.html';
        $this->writeReportHeader($htmlReportFileName);
        $orderedArray = new ScoreSorter($data, $descending);
        $orderedDrivers = $orderedArray->getResult();
        foreach ($orderedDrivers as $key => $ordered) {
            $index = $ordered['driver'];
            $flight = $data->find($index);
            $reported = '<tr><td>' . $key + 1 . '</td><td>' . $flight->getDriverName() . '</td>';
            $reported .= '<td>' . $flight->getTeam() . '</td>';
            $reported .= '<td>' . $flight->getDuration($flight->getStart(), $flight->getFinish()) . '</td></tr>';
            if ($ordered['lined']) {
                $reported .= '<tr><td>--</td><td>-----------------------------</td>';
                $reported .= '<td>------------------------------</td><td>---------</td>></tr>';
            }
            file_put_contents($htmlReportFileName, $reported, FILE_APPEND);
        }
        $this->writeReportFooter($htmlReportFileName);
    }

    private function writeReportHeader(string $htmlReportFileName): void
    {
        $toWrite = <<<'EOF'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report of Monaco 2018 Racing</title>
</head>
<body>
<table id="report">
EOF;
        file_put_contents($htmlReportFileName, $toWrite);
    }

    private function writeReportFooter(string $htmlReportFileName): void
    {
        $toWrite = '</table></body></html>';
        file_put_contents($htmlReportFileName, $toWrite, FILE_APPEND);
    }
}
