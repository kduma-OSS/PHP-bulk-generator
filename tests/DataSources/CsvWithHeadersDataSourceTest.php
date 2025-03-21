<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\Tests\DataSources;

use Kduma\BulkGenerator\DataSources\CsvWithHeadersDataSource;
use PHPUnit\Framework\TestCase;

final class CsvWithHeadersDataSourceTest extends TestCase
{
    public function testGettingHeaders()
    {
        $csvWithHeadersDataSource = new CsvWithHeadersDataSource(__DIR__ . '/../stubs/basic_csv.csv');

        $data = $csvWithHeadersDataSource->getHeaders();

        $this->assertSame(
            ['id','name','city'],
            $data
        );
    }

    public function testReadingBasicCsvFileWithHeaders()
    {
        $csvWithHeadersDataSource = new CsvWithHeadersDataSource(__DIR__ . '/../stubs/basic_csv.csv');

        $data = $csvWithHeadersDataSource->getData();

        $this->assertSame(
            [
                [
                    'id' => '1',
                    'name' => 'John',
                    'city' => 'Washington',
                ],
                [
                    'id' => '2',
                    'name' => 'Michael',
                    'city' => 'New York',
                ],
            ],
            $data
        );
    }

    public function testReadingMissingColumnsCsvFileWithHeaders()
    {
        $csvWithHeadersDataSource = new CsvWithHeadersDataSource(__DIR__ . '/../stubs/missing_columns_csv.csv');

        $data = $csvWithHeadersDataSource->getData();

        $this->assertEquals(
            [
                [
                    'id' => '1',
                    'name' => 'John',
                    'city' => null,
                ],
                [
                    'id' => '2',
                    'name' => 'Michael',
                    'city' => 'New York',
                ],
            ],
            $data
        );
    }
}
