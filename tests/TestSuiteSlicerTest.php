<?php

declare(strict_types=1);

namespace Wizaplace\PHPUnit\Slicer;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestSuite;

class TestSuiteSlicerTest extends TestCase
{
    /**
     * @var TestSuite
     */
    private static $tested;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$tested = new TestSuite();
        self::$tested->addTestFiles([
            __DIR__.'/fixtures/ATest.php',
            __DIR__.'/fixtures/BTest.php',
            __DIR__.'/fixtures/CTest.php',
        ]);
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        self::$tested = null;
    }

    public function testSliceReturnsATestSuiteObject(): void
    {
        $suite = clone self::$tested;

        $modifiedSuite = TestSuiteSlicer::slice($suite, ['currentSlice' => 1, 'totalSlices' => 2]);

        self::assertInstanceOf(TestSuite::class, $modifiedSuite);
    }

    public function testSliceFirstHalf()
    {
        $suite = clone self::$tested;
        self::assertCount(19, $suite);

        ob_start();

        $modifiedSuite = TestSuiteSlicer::slice($suite, ['currentSlice' => 1, 'totalSlices' => 2]);

        $output = ob_get_clean();

        self::assertEquals("PHPUnit suite slicer, running slice 1/2 (10 tests: from #1 to #10)\n", $output);
        self::assertCount(10, $modifiedSuite);

        $testsNames = array_map(function(TestCase $test) {
            return $test->getName();
        }, $this->extractTestsInSuite($modifiedSuite));

        self::assertEquals([
            'testA',
            'testB',
            'testC',
            'testD',
            'testE',
            'testF',
            'testG',
            'testH',
            'testI',
            'testJ',
        ], $testsNames);
    }

    public function testSliceSecondHalf()
    {
        $suite = clone self::$tested;
        self::assertCount(19, $suite);

        ob_start();

        $modifiedSuite = TestSuiteSlicer::slice($suite, ['currentSlice' => 2, 'totalSlices' => 2]);

        $output = ob_get_clean();

        self::assertEquals("PHPUnit suite slicer, running slice 2/2 (9 tests: from #11 to #19)\n", $output);
        self::assertCount(9, $modifiedSuite);

        $testsNames = array_map(function(TestCase $test) {
            return $test->getName();
        }, $this->extractTestsInSuite($modifiedSuite));

        self::assertEquals([
            'testK',
            'testL',
            'testM with data set #0',
            'testM with data set #1',
            'testM with data set #2',
            'testM with data set #3',
            'testM with data set #4',
            'testN',
            'testO',
        ], $testsNames);
    }

    public function testSliceLastThird()
    {
        $suite = clone self::$tested;
        self::assertCount(19, $suite);

        ob_start();

        $modifiedSuite = TestSuiteSlicer::slice($suite, ['currentSlice' => 3, 'totalSlices' => 3]);

        $output = ob_get_clean();

        self::assertEquals("PHPUnit suite slicer, running slice 3/3 (5 tests: from #15 to #19)\n", $output);
        self::assertCount(5, $modifiedSuite);

        $testsNames = array_map(function(TestCase $test) {
            return $test->getName();
        }, $this->extractTestsInSuite($modifiedSuite));

        self::assertEquals([
            'testM with data set #2',
            'testM with data set #3',
            'testM with data set #4',
            'testN',
            'testO',
        ], $testsNames);
    }

    private function extractTestsInSuite(TestSuite $suite)
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
