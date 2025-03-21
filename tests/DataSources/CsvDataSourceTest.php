<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\Tests\DataSources;

use Kduma\BulkGenerator\DataSources\CsvDataSource;
use PHPUnit\Framework\TestCase;

final class CsvDataSourceTest extends TestCase
{
    public function testReadingBasicCsvFile(): void
    {
        $csvDataSource = new CsvDataSource(__DIR__ . '/../stubs/basic_csv.csv');

        $data = $csvDataSource->getData();
        
        $this->assertSame(
            [
                [
                    'column_0' => 'id',
                    'column_1' => 'name',
                    'column_2' => 'city',
                ],
                [
                    'column_0' => '1',
                    'column_1' => 'John',
                    'column_2' => 'Washington',
                ],
                [
                    'column_0' => '2',
                    'column_1' => 'Michael',
                    'column_2' => 'New York',
                ],
            ], 
            $data
        );
    }
}
