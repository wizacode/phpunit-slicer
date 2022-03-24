<?php

declare(strict_types=1);

namespace Wizaplace\PHPUnit\Slicer;

use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestResult;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Runner\TestSuiteLoader;
use PHPUnit\TextUI\TestRunner as PhpUnitTestRunner;

class TestRunner
{
    /* @var PhpUnitTestRunner */
    private $phpUnitTestRunner;

    public function __construct(TestSuiteLoader $loader)
    {
        $this->phpUnitTestRunner = new PhpUnitTestRunner($loader);
    }

    public function doRun(Test $suite, array $arguments = [], $exit = true): TestResult
    {
        if (isset($arguments['totalSlices'], $arguments['currentSlice']) && $suite instanceof TestSuite) {
            TestSuiteSlicer::slice($suite, $arguments);
        }

        return $this->phpUnitTestRunner->doRun($suite, $arguments, $exit);
    }
}
