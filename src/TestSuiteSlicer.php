<?php

declare(strict_types=1);

namespace Wizaplace\PHPUnit\Slicer;

use PHPUnit\Framework\TestSuite;

class TestSuiteSlicer
{
    public static function slice(TestSuite $suite, array $arguments)
    {
        $tests = self::extractTestsInSuite($suite);

        $slices = $arguments['totalSlices'];
        $current = $arguments['currentSlice'] - 1; // 0 indexed. Slice 1 is in reality slice 0
        $total = count($tests);
        $testsPerSlice = (int) ceil($total / $slices);
        $offset = $testsPerSlice * $current;

        $suite->setTests(
            array_slice($tests, $offset, $testsPerSlice)
        );

        $lastTestId = min($offset+$testsPerSlice, $total);
        $testsInThisSlice = $lastTestId - $offset;

        echo sprintf(
            'PHPUnit suite slicer, running slice %d/%d (%d test%s: from #%d to #%d)'.PHP_EOL,
            $current+1,
            $slices,
            $testsInThisSlice,
            $testsInThisSlice > 1 ? 's' : '',
            $offset+1,
            $lastTestId
        );
    }

    private static function extractTestsInSuite(TestSuite $suite) : array
    {
        $extractedTests = [];
        $suiteItems = $suite->tests();

        foreach ($suiteItems as $item) {
            if ($item instanceof TestSuite) {
                $extractedTests = array_merge($extractedTests, self::extractTestsInSuite($item));
            } else {
                $extractedTests[] = $item;
            }
        }

        return $extractedTests;
    }
}
