<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\DataSources;


class PassthroughDataSource implements DataSourceInterface
{
    /**
     * PassthroughDataSource constructor.
     */
    public function __construct(private readonly array $data)
    {
    }
    
    public function getData(): array
    {
        return $this->data;
    }
}
