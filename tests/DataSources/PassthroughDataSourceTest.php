<?php

namespace Kduma\BulkGenerator\Tests\DataSources;

use Kduma\BulkGenerator\DataSources\PassthroughDataSource;
use PHPUnit\Framework\TestCase;
use Tightenco\Collect\Support\Collection;

class PassthroughDataSourceTest extends TestCase
{

    public function testGetData()
    {
        $data = [1, 2, 3];
        
        $passthroughDataSource = new PassthroughDataSource($data);

        $this->assertSame($data, $passthroughDataSource->getData());
    }
}
