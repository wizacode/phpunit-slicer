<?php

declare(strict_types=1);

namespace Wizaplace\PHPUnit\Slicer;

class Command extends \PHPUnit\TextUI\Command
{
    public function __construct()
    {
        $this->longOptions['slices='] = 'slicesHandler';

        // there is no parent construct
    }

    public function slicesHandler($slices)
    {
        $parts = [];
        if (!preg_match('/^([0-9]+)\/([0-9]+)$/', $slices, $parts)) {
            echo '--slices: you must provide a value like 2/4 if you want to run the second slice on a total of 4';
            exit(1);
        }

        $currentSlice = (int) $parts[1];
        $totalSlices = (int) $parts[2];

        if ($currentSlice > $totalSlices) {
            echo '--slices: current slice must be <= total slices';
            exit(1);
        }

        if ($totalSlices < 1 || $currentSlice < 1) {
            echo '--slices: the two values must be > 0';
            exit(1);
        }

        $this->arguments['currentSlice'] = $currentSlice;
        $this->arguments['totalSlices'] = $totalSlices;
    }

    protected function showHelp(): void
    {
        parent::showHelp();

        echo <<<EOT

Slices Options:

  --slices <current>/<total>  Run a specific part of the suite.

EOT;
    }

    protected function createRunner(): TestRunner
    {
        return new TestRunner($this->arguments['loader']);
    }
}
