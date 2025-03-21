<?php

namespace Kduma\BulkGenerator\Tests\DataSources;

use Kduma\BulkGenerator\DataSources\RangeCounterDataSource;
use PHPUnit\Framework\TestCase;

class RangeCounterDataSourceTest extends TestCase
{
    public function testGetDataForRisingRange()
    {
        $sut = new RangeCounterDataSource(1, 4);

        $data = $sut->getData();

        $this->assertSame([
            [
                'counter' => 1
            ], 
            [
                'counter' => 2
            ], 
            [
                'counter' => 3
            ], 
            [
                'counter' => 4
            ]
        ], $data);
    }

    public function testGetDataForFallingRange()
    {
        $sut = new RangeCounterDataSource(4, 1);

        $data = $sut->getData();

        $this->assertSame([
            [
                'counter' => 4
            ], 
            [
                'counter' => 3
            ], 
            [
                'counter' => 2
            ], 
            [
                'counter' => 1
            ]
        ], $data);
    }

    public function testGetDataForRisingRangeWithSpecifiedIncrements()
    {
        $sut = new RangeCounterDataSource(1, 4, 2);

        $data = $sut->getData();

        $this->assertSame([
            [
                'counter' => 1
            ], 
            [
                'counter' => 3
            ]
        ], $data);
    }

    public function testGetDataForFallingRangeWithSpecifiedIncrements()
    {
        $sut = new RangeCounterDataSource(4, 1, 2);

        $data = $sut->getData();

        $this->assertSame([
            [
                'counter' => 4
            ], 
            [
                'counter' => 2
            ]
        ], $data);
    }
}
