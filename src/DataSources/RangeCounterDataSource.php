<?php


namespace Kduma\BulkGenerator\DataSources;


use Tightenco\Collect\Support\Enumerable;
use Tightenco\Collect\Support\LazyCollection;

class RangeCounterDataSource implements DataSourceInterface
{
    /**
     * RangeCounterDataSource constructor.
     *
     * @param int $from
     * @param int $to
     * @param int $increment
     */
    public function __construct(private readonly int $from, private readonly int $to, private readonly int $increment = 1)
    {
    }

    public function getData(): array
    {
        return array_map(
            fn($counter) => ['counter' => $counter],
            range($this->from, $this->to, $this->increment)
        );
    }
}