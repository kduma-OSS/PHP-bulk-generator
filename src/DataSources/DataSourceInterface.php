<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\DataSources;


interface DataSourceInterface
{
    public function getData() : array;
}
