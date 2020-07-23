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

    public function getData(): Enumerable
    {
        if ($this->from > $this->to){
            return new LazyCollection(function () {
                for ($from = $this->from; $from >= $this->to; $from-=$this->increment) {
                    yield ['counter' => $from];
                }
            });
        } else {
            return new LazyCollection(function () {
                for ($from = $this->from; $from <= $this->to; $from+=$this->increment) {
                    yield ['counter' => $from];
                }
            });
        }
    }
}