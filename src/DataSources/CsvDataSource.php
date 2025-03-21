<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\DataSources;


use Tightenco\Collect\Support\Enumerable;
use Tightenco\Collect\Support\LazyCollection;

class CsvDataSource implements DataSourceInterface
{
    /**
     * CsvDataSource constructor.
     */
    public function __construct(private readonly string $filename, private readonly string $delimiter = ',', private readonly string $enclosure = '"', private readonly string $escape = '\\')
    {
    }

    public function getData(): array
    {
        $data = [];
        
        if (($handle = fopen($this->filename, "r")) !== FALSE) {
            while (($row = fgetcsv($handle, 0, $this->delimiter, $this->enclosure, $this->escape)) !== FALSE) {
                $data[] = array_merge([], ...array_map(fn($value, $index) => ['column_'.$index => $value], array_values($row), array_keys($row)));
            }

            fclose($handle);
        }
        
        return $data;
    }
}
