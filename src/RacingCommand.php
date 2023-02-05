<?php

declare(strict_types=1);

namespace App;

use App\Reporter\CliReporter;
use App\Reporter\HtmlReporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function Symfony\Component\String\b;

class RacingCommand extends Command
{
    private CliReporter $cliReport;
    private HtmlReporter $htmlReport;
    private BuildDataReport $builder;

    public function __construct(string $name = null)
    {
        $this->builder = new BuildDataReport();
        $this->cliReport = new CliReporter();
        $this->htmlReport = new HtmlReporter();
        parent::__construct($name);
    }

    public function configure(): void
    {

        $this->setName('app:report')
            ->setDescription('Report of Monaco 2018 Racing')
            ->setHelp('This command reverses Top 15 cars are going to the Q2 stage')
            ->addOption(
                'driver',
                'd',
                InputOption::VALUE_REQUIRED,
                'Pass which driver are you interested in there',
                ''
            )
            ->addOption(
                'files',
                'f',
                InputOption::VALUE_REQUIRED,
                'Pass path to file with statistics there',
                ''
            )->addOption(
                'sort',
                's',
                InputOption::VALUE_OPTIONAL,
                'shows list of drivers in order. (default order is asc)',
                'asc'
            );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {

        $output->writeln([
            '<info>======              Racing Report Console App                  =====</>',
            '<info>====================================================================</>',
            '',
        ]);

        $logsLocation = $input->getOption('files');
        if ($this->isFileExists($logsLocation, $output)) {
            $racingData = $this->builder->buildReport($logsLocation);
            if ($input->getOption('driver')) {
                $this->cliReport->printOne($racingData, $output, $input->getOption('driver'));
            } else {
                if($input->getOption('sort') === 'asc') {
                    $descending = true;
                }elseif($input->getOption('sort') === 'desc') {
                    $descending = false;
                }
                $this->cliReport->print($racingData, $output, $descending);
                $this->htmlReport->print($racingData, $logsLocation, $descending);
            }
            return Command::SUCCESS;
        } else {
            return Command::FAILURE;
        }
    }

    private function isFileExists(string $logsLocation, OutputInterface $output): bool
    {
        $fileName = '';
        $result = true;
        if (!file_exists($logsLocation . '/start.log')) {
            $fileName = 'start.log';
            $result = false;
        } else if (!file_exists($logsLocation . '/end.log')) {
            $fileName = 'end.log';
            $result = false;
        } else if (!file_exists($logsLocation . '/abbreviations.txt')) {
            $fileName = 'abbreviations.txt';
            $result = false;
        } else if (!$result) {
            $output->writeln('file ' . $fileName . ' doesn`t exists in folder  "' . $logsLocation);
        }
        return $result;
    }
}
