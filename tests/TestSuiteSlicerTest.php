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

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$tested = new TestSuite();
        self::$tested->addTestFiles([
            __DIR__.'/fixtures/ATest.php',
            __DIR__.'/fixtures/BTest.php',
            __DIR__.'/fixtures/CTest.php',
        ]);
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        self::$tested = null;
    }

    public function test slice first half()
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
            'test A',
            'test B',
            'test C',
            'test D',
            'test E',
            'test F',
            'test G',
            'test H',
            'test I',
            'test J',
        ], $testsNames);
    }

    public function test slice second half()
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
            'test K',
            'test L',
            'test M with data set #0',
            'test M with data set #1',
            'test M with data set #2',
            'test M with data set #3',
            'test M with data set #4',
            'test N',
            'test O',
        ], $testsNames);
    }

    public function test slice last third()
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
            'test M with data set #2',
            'test M with data set #3',
            'test M with data set #4',
            'test N',
            'test O',
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
