<?php


namespace Kduma\BulkGenerator\DataSources;


use Tightenco\Collect\Support\Enumerable;

interface DataSourceInterface
{
    public function getData() : Enumerable;
}