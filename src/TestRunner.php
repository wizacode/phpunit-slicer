<?php

declare(strict_types=1);

namespace Wizaplace\PHPUnit\Slicer;

use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestResult;
use PHPUnit\Framework\TestSuite;

class TestRunner extends \PHPUnit\TextUI\TestRunner
{
    public function doRun(Test $suite, array $arguments = [], $exit = true): TestResult
    {
        if (isset($arguments['totalSlices'], $arguments['currentSlice']) && $suite instanceof TestSuite) {
            $localArguments = $arguments;
            $this->handleConfiguration($localArguments);

            TestSuiteSlicer::slice($suite, $localArguments);
        }

        return parent::doRun($suite, $arguments, $exit);
    }
}
