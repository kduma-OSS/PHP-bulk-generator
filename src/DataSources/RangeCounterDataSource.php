<?php


namespace Kduma\BulkGenerator\DataSources;


use Tightenco\Collect\Support\Enumerable;
use Tightenco\Collect\Support\LazyCollection;

class RangeCounterDataSource implements DataSourceInterface
{
    private int $from;
    private int $to;
    private int $increment;

    /**
     * RangeCounterDataSource constructor.
     *
     * @param int $from
     * @param int $to
     * @param int $increment
     */
    public function __construct(int $from, int $to, int $increment = 1)
    {
        $this->from = $from;
        $this->to = $to;
        $this->increment = $increment;
    }

    public function getData(): array
    {
        return array_map(
            fn($counter) => ['counter' => $counter],
            range($this->from, $this->to, $this->increment)
        );
    }
}