<?php


namespace Kduma\BulkGenerator\PageOptions;


use Mpdf\PageFormat;

class PageSize
{
    private float $width;
    private float $height;
    private bool  $landscape;

    /**
     * @return float
     */
    public function getWidth(): float
    {
        return $this->width;
    }

    /**
     * @return float
     */
    public function getHeight(): float
    {
        return $this->height;
    }

    /**
     * @return bool
     */
    public function isLandscape(): bool
    {
        return $this->landscape;
    }

    /**
     * PageSize constructor.
     *
     * @param float $width
     * @param float $height
     * @param bool  $landscape
     */
    public function __construct(float $width, float $height, bool $landscape = false)
    {
        $this->width = $width;
        $this->height = $height;
        $this->landscape = $landscape;
    }

    public static function fromName(string $format, bool $landscape = false): PageSize
    {
        $size = PageFormat::getSizeFromName($format);
        
        return new PageSize($size[0], $size[1], $landscape);
    }
}