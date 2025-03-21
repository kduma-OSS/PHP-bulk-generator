<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\DataSources;


use Tightenco\Collect\Support\Enumerable;

interface DataSourceInterface
{
    public function getData() : array;
}
