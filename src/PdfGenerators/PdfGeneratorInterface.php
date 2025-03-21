<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\PdfGenerators;

interface PdfGeneratorInterface
{
    public function start(bool $doubleSided): void;

    public function insert(string $html, ?string $template = null): void;

    public function finish(): string;
}
