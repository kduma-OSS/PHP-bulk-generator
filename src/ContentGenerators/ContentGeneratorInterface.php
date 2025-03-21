<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\ContentGenerators;


interface ContentGeneratorInterface
{
    public function getContent(array $variables): string;
}
