<?php


namespace Kduma\BulkGenerator\DataSources;


use Tightenco\Collect\Support\Enumerable;
use Tightenco\Collect\Support\LazyCollection;

class CsvDataSource implements DataSourceInterface
{
    private string $filename;
    private string $delimiter;
    private string $enclosure;
    private string $escape;

    /**
     * CsvDataSource constructor.
     *
     * @param string $filename
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     */
    public function __construct(string $filename, string $delimiter = ',', string $enclosure = '"', string $escape = '\\')
    {
        $this->filename = $filename;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
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