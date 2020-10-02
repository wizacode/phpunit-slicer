<?php

declare(strict_types=1);

namespace Wizaplace\PHPUnit\Tests\Slicer\Fixtures;

use PHPUnit\Framework\TestCase;

class CTest extends TestCase
{
    public function test_I() { }
    public function test_J() { }
    public function test_K() { }
    public function test_L() { }

    /**
     * @dataProvider dataProvider
     */
    public function test_M($a) { }

    public function dataProvider()
    {
        return [
            [0],
            [1],
            [2],
            [3],
            [4],
        ];
    }

    public function test_N() { }
    public function test_O() { }
}
