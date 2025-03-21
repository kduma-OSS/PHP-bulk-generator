<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\DataSources;


class RangeCounterDataSource implements DataSourceInterface
{
    /**
     * RangeCounterDataSource constructor.
     */
    public function __construct(private readonly int $from, private readonly int $to, private readonly int $increment = 1)
    {
    }

    public function getData(): array
    {
        return array_map(
            fn($counter): array => ['counter' => $counter],
            range($this->from, $this->to, $this->increment)
        );
    }
}
