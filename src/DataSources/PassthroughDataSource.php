<?php


namespace Kduma\BulkGenerator\DataSources;


use Tightenco\Collect\Support\Enumerable;

class PassthroughDataSource implements DataSourceInterface
{
    private Enumerable $data;

    /**
     * PassthroughDataSource constructor.
     *
     * @param Enumerable $data
     */
    public function __construct(Enumerable $data)
    {
        $this->data = $data;
    }
    
    public function getData(): Enumerable
    {
        return $this->data;
    }
}