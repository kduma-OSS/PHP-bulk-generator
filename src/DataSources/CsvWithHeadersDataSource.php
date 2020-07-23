<?php


namespace Kduma\BulkGenerator\DataSources;


use Tightenco\Collect\Support\Enumerable;

class CsvWithHeadersDataSource extends CsvDataSource
{
    public function getHeaders(): array 
    {
        return array_values(parent::getData()->first());
    }
    
    public function getData(): Enumerable
    {
        $ds = parent::getData();
        
        $headers = collect($ds->first());
        
        return $ds->skip(1)
            ->values()
            ->map(function ($row) use ($headers) {
                return $headers->mapWithKeys(fn($key, $index) => [$key => $row[$index] ?? null])->toArray();
            });
    }
}