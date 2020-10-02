<?php

declare(strict_types=1);

namespace Wizaplace\PHPUnit\Slicer;

use PHPUnit\Framework\TestSuite;

class Command extends \PHPUnit\TextUI\Command
{
    public function __construct()
    {
        $this->longOptions['slices='] = 'slicesHandler';
    }

    public static function main(bool $exit = true): int
    {
        return (new static)->runSlicer($_SERVER['argv'], $exit);
    }

    public function runSlicer(array $argv, bool $exit = true): int
    {
        $this->handleArguments($argv);

        $runner = $this->createRunner();

        if ($this->arguments['test'] instanceof TestSuite) {
            $suite = $this->arguments['test'];
        } else {
            $suite = $runner->getTest(
                $this->arguments['test'],
                $this->arguments['testSuffixes']
            );
        }

        if (isset($this->arguments['totalSlices'], $this->arguments['currentSlice'])) {
            TestSuiteSlicer::slice($suite, $this->arguments);
        }

        $this->arguments['test'] = $suite;

        return $this->run($_SERVER['argv'], $exit);
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
}
