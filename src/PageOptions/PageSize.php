<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\PageOptions;

use Mpdf\PageFormat;

class PageSize
{
    public function __construct(
        private readonly float $width,
        private readonly float $height,
        private readonly bool $landscape = false
    ) {
    }

    public function getWidth(): float
    {
        return $this->width;
    }

    public function getHeight(): float
    {
        return $this->height;
    }

    public function isLandscape(): bool
    {
        return $this->landscape;
    }

    public static function fromName(string $format, bool $landscape = false): self
    {
        $size = PageFormat::getSizeFromName($format);

        return new self($size[0], $size[1], $landscape);
    }
}
