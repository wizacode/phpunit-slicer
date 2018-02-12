<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class CTest extends TestCase
{
    public function test I() { }
    public function test J() { }
    public function test K() { }
    public function test L() { }

    /**
     * @dataProvider dataProvider
     */
    public function test M($a) { }

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

    public function test N() { }
    public function test O() { }
}
