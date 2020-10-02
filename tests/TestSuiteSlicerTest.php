<?php

declare(strict_types=1);

namespace Wizaplace\PHPUnit\Tests\Slicer;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestSuite;
use Wizaplace\PHPUnit\Slicer\TestSuiteSlicer;

final class TestSuiteSlicerTest extends TestCase
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
            __DIR__ . '/Fixtures/ATest.php',
            __DIR__ . '/Fixtures/BTest.php',
            __DIR__ . '/Fixtures/CTest.php',
        ]);
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        self::$tested = null;
    }

    public function test_slice_first_half()
    {
        $suite = clone self::$tested;
        self::assertCount(19, $suite);

        ob_start();

        TestSuiteSlicer::slice($suite, ['currentSlice' => 1, 'totalSlices' => 2]);

        $output = ob_get_clean();

        self::assertEquals("PHPUnit suite slicer, running slice 1/2 (10 tests: from #1 to #10)\n", $output);
        self::assertCount(10, $suite);

        $testsNames = array_map(function(TestCase $test) {
            return $test->getName();
        }, $this->extractTestsInSuite($suite));

        self::assertEquals([
            'test_A',
            'test_B',
            'test_C',
            'test_D',
            'test_E',
            'test_F',
            'test_G',
            'test_H',
            'test_I',
            'test_J',
        ], $testsNames);
    }

    public function test_slice_second_half()
    {
        $suite = clone self::$tested;
        self::assertCount(19, $suite);

        ob_start();

        TestSuiteSlicer::slice($suite, ['currentSlice' => 2, 'totalSlices' => 2]);

        $output = ob_get_clean();

        self::assertEquals("PHPUnit suite slicer, running slice 2/2 (9 tests: from #11 to #19)\n", $output);
        self::assertCount(9, $suite);

        $testsNames = array_map(function(TestCase $test) {
            return $test->getName();
        }, $this->extractTestsInSuite($suite));

        self::assertEquals([
            'test_K',
            'test_L',
            'test_M with data set #0',
            'test_M with data set #1',
            'test_M with data set #2',
            'test_M with data set #3',
            'test_M with data set #4',
            'test_N',
            'test_O',
        ], $testsNames);
    }

    public function test_slice_last_third()
    {
        $suite = clone self::$tested;
        self::assertCount(19, $suite);

        ob_start();

        TestSuiteSlicer::slice($suite, ['currentSlice' => 3, 'totalSlices' => 3]);

        $output = ob_get_clean();

        self::assertEquals("PHPUnit suite slicer, running slice 3/3 (5 tests: from #15 to #19)\n", $output);
        self::assertCount(5, $suite);

        $testsNames = array_map(function(TestCase $test) {
            return $test->getName();
        }, $this->extractTestsInSuite($suite));

        self::assertEquals([
            'test_M with data set #2',
            'test_M with data set #3',
            'test_M with data set #4',
            'test_N',
            'test_O',
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
