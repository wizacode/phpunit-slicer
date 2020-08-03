<?php

declare(strict_types=1);

namespace Wizaplace\PHPUnit\Slicer;

use PHPUnit\Framework\TestResult;
use PHPUnit\Framework\TestSuite;
use Wizaplace\PHPUnit\Slicer\Vendor\PHPUnit\TextUI\TestRunner as BaseTestRunner;

class TestRunner extends BaseTestRunner
{
    public function run(TestSuite $suite, array $arguments = [], array $warnings = [], bool $exit = true): TestResult
    {
        if (isset($arguments['totalSlices'], $arguments['currentSlice']) && $suite instanceof TestSuite) {
            $localArguments = $arguments;
            $this->handleConfiguration($localArguments);

            TestSuiteSlicer::slice($suite, $localArguments);
        }

        return parent::run($suite, $arguments, $warnings, $exit);
    }
}
