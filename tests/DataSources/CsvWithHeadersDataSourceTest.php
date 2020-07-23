<?php

namespace Kduma\BulkGenerator\Tests\DataSources;

use Kduma\BulkGenerator\DataSources\CsvWithHeadersDataSource;
use PHPUnit\Framework\TestCase;

class CsvWithHeadersDataSourceTest extends TestCase
{
    public function testGettingHeaders()
    {
        $sut = new CsvWithHeadersDataSource(__DIR__ . '/basic_csv.csv');

        $data = $sut->getHeaders();

        $this->assertEquals(
            ['id','name','city'],
            $data
        );
    }
    public function testReadingBasicCsvFileWithHeaders()
    {
        $sut = new CsvWithHeadersDataSource(__DIR__ . '/basic_csv.csv');

        $data = $sut->getData();

        $this->assertEquals(
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
            $data->all()
        );
    }
    public function testReadingMissingColumnsCsvFileWithHeaders()
    {
        $sut = new CsvWithHeadersDataSource(__DIR__ . '/missing_columns_csv.csv');

        $data = $sut->getData();

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
            $data->all()
        );
    }
}
