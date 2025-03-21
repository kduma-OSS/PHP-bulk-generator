<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\Tests\DataSources;

use Kduma\BulkGenerator\DataSources\PassthroughDataSource;
use PHPUnit\Framework\TestCase;

final class PassthroughDataSourceTest extends TestCase
{

    public function testGetData(): void
    {
        $data = [1, 2, 3];
        
        $passthroughDataSource = new PassthroughDataSource($data);

        $this->assertSame($data, $passthroughDataSource->getData());
    }
}
