<?php


namespace Kduma\BulkGenerator\DataSources;


use Tightenco\Collect\Support\Enumerable;

class PassthroughDataSource implements DataSourceInterface
{
    private array $data;

    /**
     * PassthroughDataSource constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function getData(): array
    {
        return $this->data;
    }
}