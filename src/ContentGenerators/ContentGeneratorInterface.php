<?php


namespace Kduma\BulkGenerator\ContentGenerators;


interface ContentGeneratorInterface
{
    public function getContent(array $variables): string;
}