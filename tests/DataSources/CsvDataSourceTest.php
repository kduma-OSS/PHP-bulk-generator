<?php

namespace Kduma\BulkGenerator\Tests\DataSources;

use Kduma\BulkGenerator\DataSources\CsvDataSource;
use PHPUnit\Framework\TestCase;

class CsvDataSourceTest extends TestCase
{
    public function testReadingBasicCsvFile()
    {
        $sut = new CsvDataSource(__DIR__ . '/basic_csv.csv');

        $data = $sut->getData();
        
        $this->assertEquals(
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
            $data->all()
        );
    }
}
