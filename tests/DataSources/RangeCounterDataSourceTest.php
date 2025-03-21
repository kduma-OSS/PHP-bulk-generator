<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\Tests\DataSources;

use Kduma\BulkGenerator\DataSources\RangeCounterDataSource;
use PHPUnit\Framework\TestCase;

final class RangeCounterDataSourceTest extends TestCase
{
    public function testGetDataForRisingRange()
    {
        $rangeCounterDataSource = new RangeCounterDataSource(1, 4);

        $data = $rangeCounterDataSource->getData();

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
        $rangeCounterDataSource = new RangeCounterDataSource(4, 1);

        $data = $rangeCounterDataSource->getData();

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
        $rangeCounterDataSource = new RangeCounterDataSource(1, 4, 2);

        $data = $rangeCounterDataSource->getData();

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
        $rangeCounterDataSource = new RangeCounterDataSource(4, 1, 2);

        $data = $rangeCounterDataSource->getData();

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
