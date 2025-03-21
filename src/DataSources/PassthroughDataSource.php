<?php


namespace Kduma\BulkGenerator\DataSources;


use Tightenco\Collect\Support\Enumerable;

class PassthroughDataSource implements DataSourceInterface
{
    /**
     * PassthroughDataSource constructor.
     *
     * @param array $data
     */
    public function __construct(private readonly array $data)
    {
    }
    
    public function getData(): array
    {
        return $this->data;
    }
}