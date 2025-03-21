<?php

declare(strict_types=1);

namespace Kduma\BulkGenerator\PageOptions;


use Mpdf\PageFormat;

class PageSize
{
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

    /**
     * PageSize constructor.
     */
    public function __construct(private readonly float $width, private readonly float $height, private readonly bool $landscape = false)
    {
    }

    public static function fromName(string $format, bool $landscape = false): PageSize
    {
        $size = PageFormat::getSizeFromName($format);
        
        return new PageSize($size[0], $size[1], $landscape);
    }
}
