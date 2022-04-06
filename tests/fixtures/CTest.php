<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class CTest extends TestCase
{
    public function testI() { }
    public function testJ() { }
    public function testK() { }
    public function testL() { }

    /**
     * @dataProvider dataProvider
     */
    public function testM($a) { }

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

    public function testN() { }
    public function testO() { }
}
