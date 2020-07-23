<?php

namespace Kduma\BulkGenerator\Tests\DataSources;

use Kduma\BulkGenerator\DataSources\PassthroughDataSource;
use PHPUnit\Framework\TestCase;
use Tightenco\Collect\Support\Collection;

class PassthroughDataSourceTest extends TestCase
{

    public function testGetData()
    {
        $collection = new Collection();
        
        $sut = new PassthroughDataSource($collection);

        $this->assertSame($collection, $sut->getData());
    }
}
