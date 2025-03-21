<?php


namespace Kduma\BulkGenerator\DataSources;


use Tightenco\Collect\Support\Enumerable;

class CsvWithHeadersDataSource extends CsvDataSource
{
    public function getHeaders(): array 
    {
        return array_values(parent::getData()[0]);
    }
    
    public function getData(): array 
    {
        $ds = parent::getData();
        
        $headers = $ds[0];
        unset($ds[0]);
        
        return array_map(fn($row) => array_merge([], ...array_map(fn($key, $index) => [$key => $row[$index] ?? null], array_values($headers), array_keys($headers))), array_values($ds));
    }
}